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

declare(strict_types=1);

use ILIAS\Data\Factory as DataFactory;
use ILIAS\Data\Order;
use ILIAS\Data\Range;
use ILIAS\Notes\Service as NotesService;
use ILIAS\Refinery\Factory as Refinery;
use ILIAS\Taxonomy\DomainService as TaxonomyService;
use ILIAS\UI\Component\Input\Container\Filter\Standard as Filter;
use ILIAS\UI\Component\Table\Data;
use ILIAS\UI\Component\Table\DataRetrieval;
use ILIAS\UI\Component\Table\DataRowBuilder;
use ILIAS\UI\Factory as UIFactory;
use ILIAS\UI\Renderer as UIRenderer;
use ILIAS\UI\URLBuilder;

/**
 * Class QuestionsFromPoolTable
 *
 * @author Matheus Zych <mzych@databay.de>
 */
class QuestionsFromPoolTable extends ilAssQuestionList implements DataRetrieval
{
    public function __construct(
        ilDBInterface                                  $db,
        private readonly ilLanguage                    $lng,
        Refinery                                       $refinery,
        ilComponentRepository                          $component_repository,
        NotesService                                   $notes_service,
        private readonly UIFactory                     $ui_factory,
        private readonly UIRenderer                    $ui_renderer,
        private readonly DataFactory                   $data_factory,
        private readonly ilRbacSystem                  $rbac,
        private readonly TaxonomyService               $taxonomy,
        private readonly ilCtrlInterface               $ctrl,
        private readonly ilTestQuestionBrowserTableGUI $gui,
        private readonly int                           $parent_obj_id,
        private readonly int                           $request_ref_id
    ) {
        $lng->loadLanguageModule('qpl');
        parent::__construct($db, $lng, $refinery, $component_repository, $notes_service);
        $this->setAvailableTaxonomyIds($taxonomy->getUsageOfObject($parent_obj_id));
    }

    public function getTable(): Data
    {
        return
            $this->ui_factory->table()->data(
                $this->lng->txt('questions'),
                $this->getColumns(),
                $this
            )
            ->withActions($this->getActions())
            ->withId('qpt' . $this->parent_obj_id . '_' . $this->request_ref_id)
        ;
    }

    public function getFilter(ilUIService $ui_service, string $action): Filter
    {
        $lifecycle_options = array_merge(
            ['' => $this->lng->txt('qst_lifecycle_filter_all')],
            ilAssQuestionLifecycle::getDraftInstance()->getSelectOptions($this->lng)
        );

        $tax_filter_options = [
            'null' => $this->lng->txt('tax_filter_notax')
        ];

        $question_type_options = [
            '' => $this->lng->txt('filter_all_question_types')
        ];

        foreach (ilObjQuestionPool::_getQuestionTypes() as $translation => $row) {
            $question_type_options[$row['type_tag']] = $translation;
        }

        foreach($this->taxonomy->getUsageOfObject($this->parent_obj_id, true) as $tax_entry) {
            $tax = new ilObjTaxonomy($tax_entry['tax_id']);
            $children = $tax->getTree()?->getChilds($tax->getTree()?->readRootId());
            $nodes = implode('-', array_map(static fn($node) => $node['obj_id'], $children));

            $tax_filter_options[$tax_entry['tax_id'] . '-0-' . $nodes] = $tax_entry['title'];

            foreach($children as $subtax) {
                $tax_filter_options[$subtax['tax_id'] . '-' . $subtax['obj_id']] = '---' . $subtax['title'];
            }
        }

        $field_factory = $this->ui_factory->input()->field();
        $filter_inputs = [
            'title' => $field_factory->text($this->lng->txt('title')),
            'description' => $field_factory->text($this->lng->txt('description')),
            'author' => $field_factory->text($this->lng->txt('author')),
            'lifecycle' => $field_factory->select($this->lng->txt('qst_lifecycle'), $lifecycle_options),
            'type' => $field_factory->select($this->lng->txt('type'), $question_type_options),
            'commented' => $field_factory->select(
                $this->lng->txt('ass_comments'),
                [
                    ilAssQuestionList::QUESTION_COMMENTED_ONLY => $this->lng->txt('qpl_filter_commented_only'),
                    ilAssQuestionList::QUESTION_COMMENTED_EXCLUDED => $this->lng->txt('qpl_filter_commented_exclude')
                ]
            ),
            'taxonomies' => $field_factory->select($this->lng->txt('tax_filter'), $tax_filter_options)
        ];

        return $ui_service->filter()->standard(
            'question_table_filter_id',
            $action,
            $filter_inputs,
            array_fill(0, count($filter_inputs), true),
            true,
            true
        );
    }

    private function getColumns(): array
    {
        $f = $this->ui_factory->table()->column();
        $date_format = $this->data_factory->dateFormat()->withTime24($this->data_factory->dateFormat()->germanShort());
        $icon_yes = $this->ui_factory->symbol()->icon()->custom(ilUtil::getImagePath('standard/icon_checked.svg'), 'yes');
        $icon_no = $this->ui_factory->symbol()->icon()->custom(ilUtil::getImagePath('standard/icon_unchecked.svg'), 'no');

        return  [
            'title' => $f->link($this->lng->txt('title')),
            'description' => $f->text($this->lng->txt('description')),
            'ttype' => $f->text($this->lng->txt('question_type')),
            'points' => $f->number($this->lng->txt('points')),
            'author' => $f->text($this->lng->txt('author'))->withIsOptional(true, false),
            'lifecycle' => $f->text($this->lng->txt('qst_lifecycle'))->withIsOptional(true, false),
            'parent_title' => $f->text($this->lng->txt('tst_source_question_pool')),
            'taxonomies' => $f->text($this->lng->txt('qpl_settings_subtab_taxonomies')),
            'feedback' => $f->boolean($this->lng->txt('feedback'), $icon_yes, $icon_no)->withIsOptional(true, false),
            'hints' => $f->boolean($this->lng->txt('hints'), $icon_yes, $icon_no)->withIsOptional(true, false),
            'created' => $f->date($this->lng->txt('create_date'), $date_format)->withIsOptional(true, false),
            'updated' => $f->date($this->lng->txt('last_update'), $date_format)->withIsOptional(true, false)
        ];
    }

    public function getRows(
        DataRowBuilder $row_builder,
        array $visible_column_ids,
        Range $range,
        Order $order,
        ?array $filter_data,
        ?array $additional_parameters
    ): Generator {
        $no_write_access = !($this->rbac->checkAccess('write', $this->request_ref_id));

        foreach ($this->getData($order, $range) as $record) {
            $row_id = (string) $record['question_id'];
            $title = $record['title'];

            // Needs to be adjusted to navigate to the correct question
            $act = ilTestQuestionBrowserTableGUI::CMD_BROWSE_QUESTIONS;
            $uri = $this->data_factory->uri(
                ILIAS_HTTP_PATH . '/' .$this->ctrl->getLinkTarget($this->gui, $act)
            );

            [
                $url_builder,
                $action_parameter_token,
                $row_id_token
            ] = (new URLBuilder($uri))->acquireParameters(
                ['question'],
                $act,
                'id'
            );

            $to_question = $url_builder
                ->withParameter($action_parameter_token, $act)
                ->withParameter($row_id_token, $row_id)
                ->buildURI()
                ->__toString()
            ;

            if (!$record['complete']) {
                $title .= ' (' . $this->lng->txt('warning_question_not_complete') . ')';
            }
            $record['title'] = $this->ui_factory->link()->standard($title, $to_question);

            $record['lifecycle'] = ilAssQuestionLifecycle::getInstance($record['lifecycle'])->getTranslation($this->lng);

            $taxonomies = [];
            foreach ($record['taxonomies'] as $taxonomy_id => $tax_data) {
                $nodes = [];

                foreach ($tax_data as $node) {
                    $nodes[] = ilTaxonomyNode::_lookupTitle($node['node_id']);
                }

                $taxonomies[] = ilObject::_lookupTitle($taxonomy_id);
                $taxonomies[] = $this->ui_renderer->render($this->ui_factory->listing()->unordered($nodes));
            }
            $record['taxonomies'] = implode('', $taxonomies);

            $record['created'] = (new DateTimeImmutable())->setTimestamp($record['created']);
            $record['updated'] = (new DateTimeImmutable())->setTimestamp($record['tstamp']);

            yield $row_builder->buildDataRow($row_id, $record)
                ->withDisabledAction('move', $no_write_access)
                ->withDisabledAction('copy', $no_write_access)
                ->withDisabledAction('delete', $no_write_access)
                ->withDisabledAction('feedback', $no_write_access)
                ->withDisabledAction('hints', $no_write_access)
            ;
        }
    }

    public function getTotalRowCount(
        ?array $filter_data,
        ?array $additional_parameters
    ): ?int {
        $this->setParentObjId($this->parent_obj_id);
        $this->load();
        return count($this->getQuestionDataArray());
    }

    private function getData(Order $order, Range $range): array
    {
        $this->setParentObjId($this->parent_obj_id);
        $this->load();
        $data = $this->postOrder($this->getQuestionDataArray(), $order);
        [$offset, $length] = $range->unpack();
        return array_slice($data, $offset, $length > 0 ? $length : null);
    }

    private function getActions(): array
    {
        return $this->buildAction(ilTestQuestionBrowserTableGUI::CMD_INSERT_QUESTIONS, 'standard');
    }

    private function buildAction(string $act, string $type, bool $async = false): array
    {
        $uri = $this->data_factory->uri(
            ILIAS_HTTP_PATH . '/' .$this->ctrl->getLinkTarget($this->gui, $act)
        );

        [
            $url_builder,
            $action_parameter_token,
            $row_id_token
        ] = (new URLBuilder($uri))->acquireParameters(
            ['question'],
            $act,
            'ids'
        );

        $action = $this
            ->ui_factory->table()->action()
            ->$type(
                $this->lng->txt($act),
                $url_builder->withParameter($action_parameter_token, $act),
                $row_id_token
            )
        ;

        return [$act => $action->withAsync($async)];
    }

    private function postOrder(array $list, Order $order): array
    {
        [$aspect, $direction] = $order->join('', function ($i, $k, $v) {
            return [$k, $v];
        });

        usort($list, static function (array $a, array $b) use ($aspect): int {
            if (is_numeric($a[$aspect]) || is_bool($a[$aspect]) || is_array($a[$aspect])) {
                return $a[$aspect] <=> $b[$aspect];
            }

            return strcmp($a[$aspect] ?? '', $b[$aspect] ?? '');
        });

        return $direction === $order::DESC ? array_reverse($list) : $list;
    }
}
