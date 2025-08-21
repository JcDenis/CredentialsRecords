<?php

declare(strict_types=1);

namespace Dotclear\Plugin\CredentialsRecords;

use ArrayObject;
use Dotclear\Core\Backend\Listing\{
    Listing,
    Pager
};
use Dotclear\Helper\Date;
use Dotclear\Helper\Html\Html;
use Dotclear\Helper\Html\Form\{
    Component,
    Div,
    Checkbox,
    Para,
    Text
};

/**
 * @brief       CredentialsRecords credentials list class.
 * @ingroup     CredentialsRecords
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-3.0
 */
class BackendList extends Listing
{
    /**
     * Display credentials record.
     *
     * @param   int     $page           The current list page
     * @param   int     $nb_per_page    The record per page number
     * @param   string  $enclose_block  The enclose block
     * @param   bool    $filter         Filter is applied
     */
    public function display(int $page, int $nb_per_page, string $enclose_block = '%s', bool $filter = false): void
    {
        if ($this->rs->isEmpty()) {
            echo
            (new Text('p', $filter ? __('No credential matches the filter') : __('No credential')))
                ->class('info')
                ->render();

            return;
        }

        $pager = new Pager($page, (int) $this->rs_count, $nb_per_page, 10);

        $cols = new ArrayObject([
            'date' => (new Text('th', __('Date')))
                ->class('first')
                ->extra('colspan="2"'),
            'type' => (new Text('th', __('Type')))
                ->extra('scope="col"'),
            'user' => (new Text('th', __('User')))
                ->extra('scope="col"'),
            'blog' => (new Text('th', __('Blog')))
                ->extra('scope="col"'),
            'id' => (new Text('th', __('Credential')))
                ->extra('scope="col"'),
            'data' => (new Text('th', __('Data')))
                ->extra('scope="col"'),
        ]);
        $this->userColumns(My::id(), $cols);

        $lines = [];
        while ($this->rs->fetch()) {
            $lines[] = $this->line(isset($_POST['entries']) && in_array($this->uid(), $_POST['entries']));
        }

        echo
        $pager->getLinks() .
        sprintf(
            $enclose_block,
            (new Div())
                ->class('table-outer')
                ->items([
                    (new Para(null, 'table'))
                        ->items([
                            (new Text(
                                'caption',
                                $filter ?
                                sprintf(__('List of %s credentials matching the filter.'), $this->rs_count) :
                                sprintf(__('List of credentials. (%s)'), $this->rs_count)
                            )),
                            (new Para(null, 'tr'))
                                ->items(iterator_to_array($cols)),
                            (new Para(null, 'tbody'))
                                ->items($lines),
                        ]),
                ])
                ->render()
        ) .
        $pager->getLinks();
    }

    /**
     * Get a records line.
     *
     * @param   bool    $checked    Selected line
     */
    private function line(bool $checked): Para
    {
        $cols = new ArrayObject([
            'check' => (new Para(null, 'td'))
                ->class('nowrap minimal')
                ->items([
                    (new Checkbox(['entries[]'], $checked))
                        ->value(Html::escapeHTML($this->uid())),
                ]),
            'date' => (new Text('td', Html::escapeHTML(Date::dt2str(__('%Y-%m-%d %H:%M'), $this->rs->f('credential_dt')))))
                ->class('nowrap minimal'),
            'type' => (new Text('td', Html::escapeHTML($this->rs->f('credential_type'))))
                ->class('nowrap minimal'),
            'id' => (new Text('td', Html::escapeHTML($this->rs->f('credential_value') ?: __('[EMPTY]'))))
                ->class('nowrap minimal'),
            'user' => (new Text('td', Html::escapeHTML($this->rs->getUserCN())))
                ->title(Html::escapeHTML($this->rs->f('user_id')))
                ->class('nowrap minimal'),
            'blog' => (new Text('td', Html::escapeHTML($this->rs->f('blog_id') ?? __('[NULL]'))))
                ->class('nowrap minimal'),
            'data' => (new Text('td', $this->prettyData()))
                ->class('maximal'),
        ]);
        $this->userColumns(My::id(), $cols);

        return
        (new Para(null, 'tr'))
            ->class('line')
            ->items(iterator_to_array($cols));
    }

    private function uid(): string
    {
        return json_encode([$this->rs->f('credential_type'),$this->rs->f('credential_value'),$this->rs->f('user_id'),$this->rs->f('blog_id') ?? '']);
    }

    private function prettyData(): string
    {
        return nl2br(str_replace(' ', '&nbsp;', Html::escapeHTML(json_encode($this->rs->getAllData(), JSON_PRETTY_PRINT))));
    }
}
