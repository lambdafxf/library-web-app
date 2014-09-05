<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $role
 * @property array $programms
 * @property array $programmsName
 */
class User extends CActiveRecord
{
	const ROLE_USER = 'user';
	const ROLE_ADMINISTRATOR = 'administrator';

	public $programms = array();
	public $programmsName = array();
	
	public static $roles = array(
	    self::ROLE_USER => 'Пользователь',
	    self::ROLE_ADMINISTRATOR => 'Администратор'
	);
	
	
	public static function getCurrent(){
		return User::model()->findByPk(Yii::app()->user->id);
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login, password, role, programms', 'required'),
			array('login, password', 'length', 'max'=>50),
            array('programms, programmsName', 'type','type'=>'array','allowEmpty'=> false ),
            array('login', 'unique', 'allowEmpty'=> false, 'caseSensitive' => false),
			array('role', 'length', 'max'=>13),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, login, password, role', 'safe', 'on'=>'search'),
		);
	}

    /**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'login' => Yii::t('main-ui', 'Login'),
			'password' => Yii::t('main-ui', 'Password'),
			'role' => Yii::t('main-ui', 'Role'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('role',$this->role,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function getList($condition = ''){
		return CHtml::listData(self::model()->findAll($condition), 'id', 'login');
	}
	
	public function afterSave() {
		if ($this->programms){
			foreach ($this->programms as $key=>$value){
				if ($value){
					if (!UserProgramms::model()->findByAttributes(array('user_id'=> $this->id,'programm_id'=>$value))){
						$programm = new UserProgramms;
						$programm->user_id = $this->id;
						$programm->programm_id = (int)$value;
						$programm->save();
					} else {
						$programm = UserProgramms::model()->findByAttributes(array('user_id'=>$this->id,'programm_id'=>$value));
					}
					
					if (isset($this->programmsName[$key])){
						if ($programmName = ProgrammsName::model()->find('programm_id=:programm_id',array(':programm_id'=>$programm->programm_id))){
							$programmName->name = $this->programmsName[$key];
							$programmName->save();
						} else {
							$programmName = new ProgrammsName;
							$programmName->programm_id = $programm->programm_id;
							$programmName->name = $this->programmsName[$key];
							$programmName->save();
						}

					}
				}
			}
		}
	}

}