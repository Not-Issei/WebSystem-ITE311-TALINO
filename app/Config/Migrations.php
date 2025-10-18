<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Migrations extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Enable/Disable Migrations
     * --------------------------------------------------------------------------
     *
     * Migrations are enabled by default.
     */
    public bool $enabled = true;

    /**
     * --------------------------------------------------------------------------
     * Migrations Table
     * --------------------------------------------------------------------------
     *
     * This is the name of the table that will store the current migrations state.
     */
    public string $table = 'migrations';

    /**
     * --------------------------------------------------------------------------
     * Type of Migrations
     * --------------------------------------------------------------------------
     *
     * Valid options are:
     * - 'sequential' => Numbered migrations (001, 002, 003, ...)
     * - 'timestamp'  => Datetime-based migrations (2025-09-05-153000_)
     */
    public string $type = 'sequential';  
    /**
     * --------------------------------------------------------------------------
     * Timestamp Format
     * --------------------------------------------------------------------------
     *
     * Used only when $type = 'timestamp'.
     */
    public string $timestampFormat = 'Y-m-d-His_';
}
