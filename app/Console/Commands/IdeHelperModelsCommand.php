<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class IdeHelperModelsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ide-helper:models {--write} {--nowrite} {--reset} {--ignore=} {--dir=} {--write_mixin} {--write_eloquent_model} {--write_phpdoc} {--write_magic_phpdoc} {--write_model_relation_count_properties} {--write_model_external_eloquent_trait} {--write_model_relation_properties} {--write_model_property_batch_size} {--write_model_property_casts} {--write_model_property_table} {--write_model_property_connection} {--write_model_property_primary_key} {--write_model_property_key_type} {--write_model_property_incrementing} {--write_model_property_with} {--write_model_property_fillable} {--write_model_property_guarded} {--write_model_property_dates} {--write_model_property_hidden} {--write_model_property_visible} {--write_model_property_appends} {--write_model_property_attributes} {--write_model_property_original} {--write_model_property_changes} {--write_model_property_relations} {--write_model_property_touches} {--write_model_property_timestamps} {--write_model_property_soft_deletes} {--write_model_property_remember_token} {--write_model_property_force_deleted_at} {--write_model_property_force_deleted_at_column} {--write_model_property_force_deleted_at_connection} {--write_model_property_force_deleted_at_table} {--write_model_property_force_deleted_at_primary_key} {--write_model_property_force_deleted_at_key_type} {--write_model_property_force_deleted_at_incrementing} {--write_model_property_force_deleted_at_with} {--write_model_property_force_deleted_at_fillable} {--write_model_property_force_deleted_at_guarded} {--write_model_property_force_deleted_at_dates} {--write_model_property_force_deleted_at_hidden} {--write_model_property_force_deleted_at_visible} {--write_model_property_force_deleted_at_appends} {--write_model_property_force_deleted_at_attributes} {--write_model_property_force_deleted_at_original} {--write_model_property_force_deleted_at_changes} {--write_model_property_force_deleted_at_relations} {--write_model_property_force_deleted_at_touches} {--write_model_property_force_deleted_at_timestamps} {--write_model_property_force_deleted_at_soft_deletes} {--write_model_property_force_deleted_at_remember_token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'IDE Helper Models command placeholder - Laravel IDE Helper not installed';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Laravel IDE Helper is not installed in this project.');
        $this->info('To install it, run: composer require --dev barryvdh/laravel-ide-helper');
        $this->info('Note: This may cause dependency conflicts with Laravel 7.');

        return 0;
    }
}
