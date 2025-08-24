<?php

declare(strict_types=1);

namespace Dotclear\Plugin\CredentialsRecords;

use ArrayObject;
use Dotclear\App;
use Dotclear\Core\Process;
use Dotclear\Core\Backend\Favorites;

/**
 * @brief       CredentialsRecords backend class.
 * @ingroup     CredentialsRecords
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-3.0
 */
class Backend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::BACKEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        My::addBackendMenuItem(App::backend()->menus()::MENU_SYSTEM);

        App::behavior()->addBehaviors([
            // backend user preference for credentials list columns
            'adminColumnsListsV2' => function (ArrayObject $cols): void {
                $cols[My::id()] = [
                    My::name(),
                    [
                        'date' => [true, __('Date')],
                        'type' => [true, __('Type')],
                        'user' => [true, __('User')],
                        'blog' => [true, __('Blog')],
                        'id'   => [true, __('Credential')],
                        'data' => [true, __('Data')],
                    ],
                ];
            },
            // backend filter for credentials list sort
            'adminFiltersListsV2' => function (ArrayObject $sorts): void {
                $sorts[My::id()] = [
                    My::name(),
                    [
                        __('Date')       => 'credential_dt',
                        __('Type')       => 'credential_type',
                        __('User')       => 'user_id',
                        __('Blog')       => 'blog_id',
                        __('Credential') => 'credential_value',
                    ],
                    'credential_dt',
                    'desc',
                    [__('Logs per page'), 30],
                ];
            },
            // backend user preference for dashboard icon
            'adminDashboardFavoritesV2' => function (Favorites $favs): void {
                $favs->register(My::id(), [
                    'title'      => My::name(),
                    'url'        => My::manageUrl(),
                    'small-icon' => My::icons(),
                    'large-icon' => My::icons(),
                    //'permissions' => null,
                ]);
            },
        ]);

        return true;
    }
}
