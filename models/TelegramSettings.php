<?php

namespace webstik\telegramNotifications\models;

use Yii;
use webstik\telegramNotifications\models\query\TelegramSettingsQuery;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * This is the model class for table "telegram_settings".
 *
 * @property integer $id
 * @property string $webhook_url
 * @property integer $do_logs
 * @property string $token
 * @property integer $PIN_code
 */
class TelegramSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'telegram_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['webhook_url', 'token', 'PIN_code'], 'required'],
            [['do_logs', 'PIN_code'], 'integer'],
            [['webhook_url', 'token'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'webhook_url' => 'Webhook Url',
            'do_logs' => 'Do Logs',
            'token' => 'Token',
            'PIN_code' => 'Pin Code',
        ];
    }

    /**
     * @inheritdoc
     * @return TelegramSettingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TelegramSettingsQuery(get_called_class());
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
        if ($insert || isset($changedAttributes['webhook_url'])) {
            try {
                $telegram = new Telegram($this->token);
                $telegram->setWebhook($this->webhook_url);
            } catch (TelegramException $e) {
            }

        }
    }

    static public function getSettings()
    {
        if (($model = TelegramSettings::findOne(1)) !== null) {
            return $model;
        } else {
            return new TelegramSettings(['id' => 1]);
        }
    }
}
