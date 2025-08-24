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

$this->registerModule(
    "Credentials",
    'Manage Dotclear credentials records',
    'Jean-Christian Denis and Contributors',
    '0.2',
    [
        'requires'    => [['core', '2.36']],
        'permissions' => null,
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $this->id . '/',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . $this->id . '/master/dcstore.xml',
        'date'        => '2025-08-24T16:24:11+00:00',
    ]
);
