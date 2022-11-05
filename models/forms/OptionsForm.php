<?php

namespace app\models\forms;

use app\models\Category;
use app\models\User;
use app\models\UserCategory;
use TaskForce\exceptions\FileUploadException;
use TaskForce\exceptions\ModelSaveException;
use Yii;
use yii\base\Model;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\web\UploadedFile;

class OptionsForm extends Model
{
    public $login;
    public $email;
    public $birthDate;
    public $phone;
    public $telegram;
    public $description;
    public $userCategory;
    public $file;
    private $filePath;
    const PHONE_NUM_LENGTH = 11;
    const TELEGRAM_LENGTH = 64;

    /**
     * Возвращает массив правил валидации
     * @return array
     */
    public function rules(): array
    {
        return [
            [['login', 'email'], 'required'],
            [['email'], 'email'],
            [['description'], 'string'],
            [['phone'], 'string', 'length' => [self::PHONE_NUM_LENGTH, self::PHONE_NUM_LENGTH]],
            [['telegram'], 'string', 'length' => [0, self::TELEGRAM_LENGTH]],
            [['birthDate'], 'date', 'format' => 'php:Y-m-d'],
            [['userCategory'], 'each', 'rule' => ['exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['userCategory' => 'id']]],
            [['file'], 'file'],
        ];
    }

    /**
     * Возвращает массив лейблов для аттрибутов
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'login' => 'Ваше имя',
            'email' => 'Email',
            'birthDate' => 'День рождения',
            'phone' => 'Номер телефона',
            'telegram' => 'Telegram',
            'description' => 'Информация о себе',
            'userCategory' => 'Выбор специализации',
            'file' => 'Сменить аватар'
        ];
    }

    /**
     * Сохраняет в найденного пользователя все из формы настроек
     * @return void
     * @throws FileUploadException Загрузка Аватара переданного пользователем не удалась
     * @throws ModelSaveException Сохранения изменений юзера не удались
     * @throws \yii\db\Exception Не удалось провести транзакцию
     */
    public function loadToUser(): void
    {
        if (!$this->uploadFile() && $this->file) {
            throw new FileUploadException('Загрузить файл не удалось');
        }

        $user = User::findOne(Yii::$app->user->id);
        $user->email = $this->email;
        $user->login = $this->login;
        $user->bdate = $this->birthDate;
        $user->phone = $this->phone;
        $user->telegram = $this->telegram;
        $user->description = $this->description;
        $user->avatar = $this->filePath;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!empty($this->userCategory)) {
                $this->loadUserCategory();
            }
            if (!$user->save()) {
                throw new ModelSaveException('Не удалось сохранить модель User');
            }
            $transaction->commit();
        } catch (ModelSaveException $exception) {
            $transaction->rollback();
            throw new ModelSaveException($exception->getMessage());
        }

    }

    /**
     * Загрузка категорий пользователя
     * @return void
     */
    public function loadUserCategory(): void
    {
        UserCategory::deleteByUser(Yii::$app->user->id);

        foreach ($this->userCategory as $category) {
            $userCategory = new UserCategory();
            $userCategory->user_id = Yii::$app->user->id;
            $userCategory->category_id = $category;
            $userCategory->save();
        }
    }

    /**
     * Загрузка файла пользователя
     * @return bool
     */
    private function uploadFile(): bool
    {
        if ($this->file && $this->validate()) {
            $newName = uniqid('upload') . '.' . $this->file->getExtension();
            $this->file->saveAs('@webroot/uploads/' . $newName);

            $this->filePath = $newName;
            return true;
        }
        return false;
    }
}