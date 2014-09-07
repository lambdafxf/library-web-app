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
			array('name', 'required'),
			array('name', 'length', 'min'=>5, 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, created, updated', 'safe', 'on'=>'search'),
		);
	}
	
	public function beforeSave() 
	{
		$this->created = $this->_created;
		if ($this->isNewRecord)
			$this->created = new CDbExpression('UNIX_TIMESTAMP()');
		
		$this->updated = new CDbExpression('UNIX_TIMESTAMP()');
	 
		return parent::beforeSave();
	}
	
	public function afterFind() 
	{
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
	
	public function behaviors()
	{
        return array('CSaveRelationsBehavior' => array('class' => 'application.components.CSaveRelationsBehavior'));
	}
	
	protected function _getBooks($author, $select='*', $index=false)
	{
		$withParams = array(
						'together'=>true,
						'select'=>$select	);
		if($index) {
			$withParams['index'] = $index;
		}
							
		if(is_numeric($author)) {
			$ret = Author::model()->with( array('books'=>$withParams) )->findByPk($author);
		} else {
			$ret = $this->books($withParams);
		}
		
		return $ret;
	}
	
	public function getBooks ($select='*', $index=false)
	{
		return $this->_getBooks(null, $select, $index);
	}
	
	public function withBooks($id, $select='*', $index=false)
	{
		return Author::_getBooks($id, $select, $index);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'created' => 'Creation date',
			'updated' => 'Update date',
			'books'	  => 'Books',
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
	
	/**
	 * Получаем список авторов, чьи книги в данный момент читает более трех читателей.
	 * @return CSqlDataProvider
	 */
	public function mostPopular()
	{
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
		
		$count=Yii::app()->db->createCommand($sql_count)->queryScalar();
		
		
		$dataProvider=new CSqlDataProvider($sql, array(
			'totalItemCount'=>$count,
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
