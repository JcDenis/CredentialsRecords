<?php
/**
 * @file
 * @brief       The plugin CredentialsRecords definition
 * @ingroup     CredentialsRecords
 *
 * @defgroup    CredentialsRecords Plugin CredentialsRecords.
 *
 * Displays Dotclear credentials records.
 *
 * @author      Jean-Christian Denis
 * @copyright   AGPL-3.0
 */
declare(strict_types=1);

if (!isset($this) || !is_object($this) || !method_exists($this, 'registerModule') || !isset($this->id) || !is_string($this->id)) {
    return;
}

$this->registerModule(
    "Credentials",
    'Manage Dotclear credentials records',
    'Jean-Christian Denis and Contributors',
    '0.6',
    [
        'requires'    => [['core', '2.39']],
        'permissions' => null,
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $this->id . '/',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . $this->id . '/master/dcstore.xml',
        'date'        => '2026-11-11T09:23:57+00:00',
    ]
);
