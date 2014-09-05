<?php

/**
 * This is the model class for table "authors".
 *
 * The followings are the available columns in table 'authors':
 * @property integer $id
 * @property string $name
 * @property integer $created
 * @property integer $updated
 */
class Author extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'authors';
	}
	protected $_created;
	protected $_updated;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required', 'message'=>'Заполните имя'),
			array('name', 'length', 'min'=>5, 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, created, updated', 'safe', 'on'=>'search'),
		);
	}
	
	public function beforeSave() {
		$this->created = $this->_created;
		if ($this->isNewRecord)
			$this->created = new CDbExpression('UNIX_TIMESTAMP()');
		
		$this->updated = new CDbExpression('UNIX_TIMESTAMP()');
	 
		return parent::beforeSave();
	}
	
	// public function getCreated($format='H:i:s d.m.Y') {
		// return date($format, $this->created);
	// }
	
	// public function updated($format='H:i:s d.m.Y') {
		// return date($format, $this->created);
	// }
	
	public function afterFind() {
		$this->_created = $this->created;
		$this->_updated = $this->updated;
		  $this->created = date('H:i:s d.m.Y' , $this->created);
		  $this->updated = date('H:i:s d.m.Y' , $this->updated);
		   // native php date method so sub the first param with your locale string  
		  return parent::afterFind(); 
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'books'=>array(self::MANY_MANY, 'Book','ab(author, book)'),
		);
	}
	
	public function behaviors(){
        return array('CSaveRelationsBehavior' => array('class' => 'application.components.CSaveRelationsBehavior'));
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Имя',
			'created' => 'Дата создания',
			'updated' => 'Дата изменения',
			'books'	  => 'Книги',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function mostPopular()
	{
		$offest = 0;
		$limit = 20;
		$order = 'name';
		$direction = 'ASC';
		
		$sql_count = 'SELECT COUNT(*) FROM ab
						INNER JOIN 
						(SELECT book FROM rb GROUP BY book HAVING(COUNT(reader)>3)) as q1 
						ON q1.book = ab.book';
		
		$sql = '
			SELECT authors.* FROM authors, 
			(SELECT ab.author as author FROM ab
				INNER JOIN 
				(SELECT book FROM rb GROUP BY book HAVING(COUNT(reader)>3)) as q1 
				ON q1.book = ab.book ) as q4
			WHERE authors.id = q4.author
		';
		// ORDER BY :order :direction 
		// LIMIT 0,20
		
		// $authors = Yii::app()->db
							// ->createCommand($sql, array(
													// 'offset'=>$offest,
													// 'limit'=>$limit,
													// 'order'=>$order,
													// 'direction'=>$direction) )
							// ->queryAll();
							
		$params = array(
						'offset'=>$offest,
						'limit'=>$limit,
						'order'=>$order,
						'direction'=>$direction);
		// $cmd = Yii::app()->db
							// ->createCommand($sql);
		// // foreach($params as $key=>$val)
			// // $cmd->bindParam($key,$val );
							
		// $authors = $cmd->queryAll();
		
		// $dataProvider = new CActiveDataProvider('Author');
		// $dataProvider->setData($authors);
		
		// $count = 16;
		$count=Yii::app()->db->createCommand($sql_count)->queryScalar();
		// $dataProvider=new CArrayDataProvider($authors, array(
		  // 'id'=>'author',
		  // 'sort'=>array(
			 // 'attributes'=>array(
			   // 'id', 'title' // Attributes has to be row name of my sql query result
				// ),
		  // ),
		  // 'pagination'=>array(
				 // 'pageSize'=>50,
				  // ),
		// ));
		
		$dataProvider=new CSqlDataProvider($sql, array(
			'totalItemCount'=>$count,
			'sort'=>array(
				'attributes'=>array(
					 'id', 'name', 'updated',
				),
			),
			'pagination'=>array(
				'pageSize'=>10,
			),
		));
		
		return $dataProvider;
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Author the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
