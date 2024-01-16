<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

/**
 * Trait ilObjFileMetadata
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
trait ilObjFileMetadata
{
    protected ?bool $no_meta_data_creation = null;

    protected function updateFileData(): void
    {
        global $DIC;
        $check_existing = $DIC->database()->queryF(
            'SELECT file_id FROM file_data WHERE file_id = %s',
            ['integer'],
            [$this->getId()]
        );
        if ($check_existing->numRows() === 0) {
            $DIC->database()->insert('file_data', $this->getArrayForDatabase());
        } else {
            $DIC->database()->update(
                'file_data',
                $this->getArrayForDatabase(),
                ['file_id' => ['integer', $this->getId()]]
            );
        }
    }

    /**
     * The basic properties of a file object are stored in table object_data.
     * This is not sufficient for a file object. Therefore we create additional
     * properties in table file_data.
     * This method has been put into a separate operation, to allow a WebDAV Null resource
     * (class.ilObjNull.php) to become a file object.
     */
    public function createProperties(bool $a_upload = false): void
    {
        global $DIC;

        // New Item
        if (isset($this->ref_id)) {
            $default_visibility = ilNewsItem::_getDefaultVisibilityForRefId($this->ref_id);
            if ($default_visibility === "public") {
                ilBlockSetting::_write("news", "public_notifications", 1, 0, $this->getId());
            }
        }
        $this->updateFileData();

        //add metadata to database
        $metadata = [
            'meta_lifecycle_id' => ['integer', $DIC->database()->nextId('il_meta_lifecycle')],
            'rbac_id' => ['integer', $this->getId()],
            'obj_id' => ['integer', $this->getId()],
            'obj_type' => ['text', "file"],
            'meta_version' => ['integer', (int) $this->getVersion()],
        ];
        $DIC->database()->insert('il_meta_lifecycle', $metadata);

        // no meta data handling for file list files
        if ($this->getMode() !== self::MODE_FILELIST) {
            $this->createMetaData();
        }
    }

    public function setNoMetaDataCreation(bool $a_status)
    {
        $this->no_meta_data_creation = $a_status;
    }

    protected function beforeCreateMetaData(): bool
    {
        return !(bool) $this->no_meta_data_creation;
    }

    protected function beforeUpdateMetaData(): bool
    {
        return !(bool) $this->no_meta_data_creation;
    }

    /**
     * create file object meta data
     */
    protected function doCreateMetaData(): void
    {
        return;   // add technical section with file size and format
        $md_obj = new ilMD($this->getId(), 0, $this->getType());
        $technical = $md_obj->addTechnical();
        $technical->setSize($this->getFileSize());
        $technical->save();
        $format = $technical->addFormat();
        $format->setFormat($this->getFileType());
        $format->save();
        $technical->update();
    }

    protected function beforeMDUpdateListener(string $a_element): bool
    {
        // Check file extension
        // Removing the file extension is not allowed
        $md = new ilMD($this->getId(), 0, $this->getType());
        if (!is_object($md_gen = $md->getGeneral())) {
            return false;
        }
        $title = $this->checkFileExtension($this->getFileName(), $md_gen->getTitle());
        $md_gen->setTitle($title);
        $md_gen->update();

        return true;
    }

    protected function doMDUpdateListener(string $a_element): void
    {
        // handling for technical section
        switch ($a_element) {
            case 'Technical':

                // Update Format (size is not stored in db)
                $md = new ilMD($this->getId(), 0, $this->getType());
                if (!is_object($md_technical = $md->getTechnical())) {
                    return;
                }

                foreach ($md_technical->getFormatIds() as $id) {
                    $md_format = $md_technical->getFormat($id);
                    $this->setFileType($md_format->getFormat());
                    break;
                }

                break;
        }
    }

    /**
     * update meta data
     */
    protected function doUpdateMetaData(): void
    {
        global $DIC;
        $md_obj = new ilMD($this->getId(), 0, $this->getType());
        if (!is_object($technical = $md_obj->getTechnical())) {
            $technical = $md_obj->addTechnical();
            $technical->save();
        }
        $technical->setSize($this->getFileSize());

        $format_ids = $technical->getFormatIds();
        if (count($format_ids) > 0) {
            $format = $technical->getFormat($format_ids[0]);
            $format->setFormat($this->getFileType());
            $format->update();
        } else {
            $format = $technical->addFormat();
            $format->setFormat($this->getFileType());
            $format->save();
        }
        $technical->update();

        $meta_version_column = ['meta_version' => ['integer', (int) $this->getVersion()]];
        $DIC->database()->update('il_meta_lifecycle', $meta_version_column, [
            'rbac_id' => [
                'integer',
                $this->getId(),
            ],
        ]);
    }

    /**
     * update copyright meta data
     */
    protected function updateCopyright(): void
    {
        $copyright_id = $this->getCopyrightID();
        if (!ilMDSettings::_getInstance()->isCopyrightSelectionActive() || $copyright_id === null) {
            return;
        }

        $md_obj = new ilMD($this->getId(), 0, $this->getType());
        $rights = $md_obj->getRights();
        if ($rights === null) {
            $rights = $md_obj->addRights();
            $rights->save();
        }

        $rights->setCopyrightAndOtherRestrictions("Yes");
        $rights->setDescription('il_copyright_entry__' . IL_INST_ID . '__' . $copyright_id);
        $rights->update();
    }

}
