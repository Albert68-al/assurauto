<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, number, boolean, json, file
            $table->string('group')->default('general'); // general, notification, security, backup
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insérer les paramètres par défaut
        DB::table('settings')->insert([
            // Paramètres généraux
            [
                'key' => 'app_name',
                'value' => 'AssurAuto',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Nom de l\'application',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'app_logo',
                'value' => null,
                'type' => 'file',
                'group' => 'general',
                'description' => 'Logo de l\'application',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'app_email',
                'value' => 'contact@assurauto.com',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Email de contact',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'app_phone',
                'value' => '+243 000 000 000',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Téléphone de contact',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'app_address',
                'value' => 'Kinshasa, RDC',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Adresse de l\'entreprise',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'default_currency',
                'value' => 'USD',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Devise par défaut',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'currencies',
                'value' => json_encode(['USD', 'CDF', 'EUR']),
                'type' => 'json',
                'group' => 'general',
                'description' => 'Devises acceptées',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Notifications Email
            [
                'key' => 'email_notifications_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Activer les notifications par email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'email_new_user',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Email lors de la création d\'un utilisateur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'email_new_police',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Email lors de la création d\'une police',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'email_new_sinistre',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Email lors de la déclaration d\'un sinistre',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Notifications SMS
            [
                'key' => 'sms_notifications_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Activer les notifications par SMS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sms_api_key',
                'value' => null,
                'type' => 'text',
                'group' => 'notification',
                'description' => 'Clé API SMS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sms_sender_name',
                'value' => 'AssurAuto',
                'type' => 'text',
                'group' => 'notification',
                'description' => 'Nom de l\'expéditeur SMS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Notifications In-App
            [
                'key' => 'inapp_notifications_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Activer les notifications in-app',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Sécurité
            [
                'key' => 'password_min_length',
                'value' => '8',
                'type' => 'number',
                'group' => 'security',
                'description' => 'Longueur minimale du mot de passe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'password_require_uppercase',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'Exiger une majuscule dans le mot de passe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'password_require_number',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'Exiger un chiffre dans le mot de passe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'password_require_special',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'Exiger un caractère spécial dans le mot de passe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'session_lifetime',
                'value' => '120',
                'type' => 'number',
                'group' => 'security',
                'description' => 'Durée de session en minutes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'type' => 'number',
                'group' => 'security',
                'description' => 'Nombre maximum de tentatives de connexion',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'two_factor_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'security',
                'description' => 'Activer l\'authentification à deux facteurs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Sauvegarde
            [
                'key' => 'backup_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'backup',
                'description' => 'Activer les sauvegardes automatiques',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'backup_frequency',
                'value' => 'daily',
                'type' => 'text',
                'group' => 'backup',
                'description' => 'Fréquence des sauvegardes (daily, weekly, monthly)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'backup_retention_days',
                'value' => '30',
                'type' => 'number',
                'group' => 'backup',
                'description' => 'Nombre de jours de conservation des sauvegardes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
