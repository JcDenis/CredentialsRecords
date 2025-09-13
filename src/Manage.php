<?php

declare(strict_types=1);

namespace Dotclear\Plugin\CredentialsRecords;

use Dotclear\App;
use Dotclear\Helper\Process\TraitProcess;
use Dotclear\Core\Backend\{
    Notices,
    Page
};
use Dotclear\Database\Statement\DeleteStatement;
use Dotclear\Helper\Html\Form\{
    Div,
    Form,
    Hidden,
    Para,
    Submit,
    Text
};
use Exception;

/**
 * @brief       CredentialsRecords manage class.
 * @ingroup     CredentialsRecords
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-3.0
 */
class Manage
{
    use TraitProcess;

    public static function init(): bool
    {
        return self::status(My::checkContext(My::MANAGE));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        $current = ManageVars::init();

        // Delete credentials
        if ($current->selected_credentials && !empty($current->entries)) {
            try {
                foreach ($current->entries as $entry) {
                    if (count($entry) != 4) {
                        continue;
                    }
                    $sql = new DeleteStatement();
                    $sql
                        ->from(App::db()->con()->prefix() . App::credential()::CREDENTIAL_TABLE_NAME)
                        ->where('credential_type = ' . $sql->quote($entry[0]))
                        ->and('credential_value = ' . $sql->quote($entry[1]))
                        ->and('user_id =' . $sql->quote($entry[2]))
                        ;

                    if (empty($entry[3])) {
                        $sql->and($sql->isNull('blog_id'));
                    } else {
                        $sql->and('blog_id =' . $sql->quote($entry[3]));
                    }

                    $sql->delete();
                }
                Notices::addSuccessNotice(__('Selected credentials have been successfully deleted'));
                My::redirect();
            } catch (Exception $e) {
                App::error()->add($e->getMessage());
            }
        }

        return true;
    }

    public static function render(): void
    {
        if (!self::status()) {
            return;
        }

        $current = ManageVars::init();

        Page::openModule(
            My::name(),
            Page::jsJson(My::id() . '_msg', [
                'confirm_delete_selected_credential' => __('Are you sure you want to delete selected credentials?'),
            ]) .
            $current->filter->js((string) My::manageUrl([], '&')) .
            My::jsLoad('backend')
        );

        echo
        Page::breadcrumb(
            [
                __('System') => '',
                My::name()   => My::manageUrl(),
            ]
        ) .
        Notices::getNotices();

        if ($current->credentials !== null && $current->list != null) {
            if ($current->credentials->isEmpty() && !$current->filter->show()) {
                echo
                (new Text('p', __('There are no credentials')))
                    ->render();
            } else {
                $current->filter->display(
                    'admin.plugin.' . My::id(),
                    (new Hidden(['p'], My::id()))
                        ->render()
                );
                $current->list->display(
                    is_numeric($current->filter->__get('page')) ? (int) $current->filter->__get('page') : 1,
                    is_numeric($current->filter->__get('nb')) ? (int) $current->filter->__get('nb') : 10,
                    (new Form(My::id() . '_form'))
                        ->action(My::manageUrl())
                        ->method('post')
                        ->fields([
                            (new Text('', '%s')),
                            (new Div())
                                ->class('two-cols')
                                ->items([
                                    (new Para())
                                        ->class('col checkboxes-helpers'),
                                    (new Para())
                                        ->class('col right')
                                        ->separator('&nbsp;')
                                        ->items([
                                            (new Submit('selected_credentials'))
                                                ->class('delete')
                                                ->value(__('Delete selected credentials')),
                                        ]),
                                    ... My::hiddenFields($current->filter->values()),
                                ]),
                        ])->render(),
                    $current->filter->show()
                );
            }
        }

        Page::closeModule();
    }
}
