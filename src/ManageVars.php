<?php

declare(strict_types=1);

namespace Dotclear\Plugin\CredentialsRecords;

use Dotclear\App;
use Dotclear\Core\Backend\Filter\{
    Filters,
    FiltersLibrary
};
use Dotclear\Database\MetaRecord;
use Exception;

/**
 * @brief       CredentialsRecords properties helper.
 * @ingroup     CredentialsRecords
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-3.0
 */
class ManageVars
{
    /**
     * ManageVars instance.
     *
     * @var     ManageVars  $instance
     */
    private static ManageVars $instance;

    /**
     * The filter instance.
     *
     * @var     Filters     $filter
     */
    public readonly Filters $filter;

    /**
     * The current records.
     *
     * @var     null|MetaRecord     $credentials
     */
    public readonly ?MetaRecord $credentials;

    /**
     * The records list form instance.
     *
     * @var     null|BackendList    $list
     */
    public readonly ?BackendList $list;

    /**
     * The post form selected entries.
     *
     * @var     array<int,array<int,string>>   $entries
     */
    public readonly array $entries;

    /**
     * The post form action.
     *
     * @var     bool    $selected_credentials
     */
    public readonly bool $selected_credentials;

    /**
     * Constructor grabs post form value and sets properties.
     */
    protected function __construct()
    {
        $entries       = !empty($_POST['entries']) && is_array($_POST['entries']) ? $_POST['entries'] : [];
        foreach ($entries as $k => $entry) {
            $entries[$k] = json_decode($entry, true);
            if (!is_array($entries[$k]) || $entries[$k] === []) {
                unset($entries[$k]);
            }
        }
        $this->entries              = $entries;
        $this->selected_credentials = isset($_POST['selected_credentials']);

        $this->filter = new Filters(My::id());
        $this->filter->add(FiltersLibrary::getPageFilter());
        $this->filter->add(FiltersLibrary::getInputFilter('credential_type', __('Type:')));
        $this->filter->add(FiltersLibrary::getInputFilter('user_id', __('User:')));
        $this->filter->add(FiltersLibrary::getInputFilter('blog_id', __('Blog:')));
        $this->filter->add(FiltersLibrary::getInputFilter('credential_value', __('Credential:')));
        $params = $this->filter->params();

        if (!isset($params['credential_type'])) {
            $params['credential_type'] = '';
        }
        if (!isset($params['blog_id'])) {
            $params['blog_id'] = '';
        }

        try {
            $this->credentials = App::credential()->getCredentials($params);
            $count      = App::credential()->getCredentials($params, true)->f(0);
            $count      = is_numeric($count) ? (int) $count : 0;
            $this->list = new BackendList($this->credentials, $count);
        } catch (Exception $e) {
            App::error()->add($e->getMessage());
        }
    }

    /**
     * Get instance.
     *
     * @return  ManageVars  The instance
     */
    public static function init(): ManageVars
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
