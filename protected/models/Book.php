<?php

/**
 * This is the model class for table "books".
 *
 * The followings are the available columns in table 'books':
 * @property integer $id
 * @property string $title
 * @property integer $created
 * @property integer $updated
 */
class Book extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'books';
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
			array('title', 'required', 'message'=>'Введите название'),
			array('title', 'length', 'min'=>5,'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, created, updated', 'safe', 'on'=>'search'),
		);
	}

	public function beforeSave() {
		$this->created = $this->_created;
		if ($this->isNewRecord)
			$this->created = new CDbExpression('UNIX_TIMESTAMP()');
		
		$this->updated = new CDbExpression('UNIX_TIMESTAMP()');
	 
		return parent::beforeSave();
	}
	
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
			'authors'=>array(self::MANY_MANY, 'Author','ab(author, book)'),
			'readers'=>array(self::MANY_MANY, 'Reader','rb(reader, book)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'created' => 'Creation date',
			'updated' => 'Update date',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function coAuthorship()
	{
		$sql_count = 'SELECT COUNT(DISTINCT(rb.book)) as book FROM rb
						INNER JOIN (
							SELECT book FROM ab GROUP BY book HAVING(COUNT(author)>=3)
						) as q1 
						ON rb.book = q1.book';
						
		$sql = 'SELECT books.* FROM books,
					(SELECT DISTINCT(rb.book) as book FROM rb
					INNER JOIN (
						SELECT book FROM ab GROUP BY book HAVING(COUNT(author)>=3)
					) as q1 
					ON rb.book = q1.book ) as q2
				WHERE books.id = q2.book';
					
		$count=Yii::app()->db->createCommand($sql_count)->queryScalar();
		
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
	 * Возвращает случайный набор книг из диапазона $count со случайным смещением
	 * Использует prepared statements; 
	 * при общем числе записей в books меньшем заданого диапазона $count может 
	 * возвращать пустой объект (не рассматривается в контексте задачи - заполнение из migrate)
	 * Альтернативные варианты:
	 * - реализация в два запроса, с корректировкой $count на общее кол-во записей в базе - в приложении
	 * - через хранимую процедуру
	 * @return CArrayDataProvider
	 */
	public function randomBook()
	{		
		$count=50; // Задает разброс случайных значений.
		$limit=5;
		
		$sql_i = 'SET @offset = (SELECT ABS(CEIL(RAND() * (COUNT( id ) - '.$count.'))) FROM books)';
		$sql_p = 'PREPARE STMT FROM \'SELECT * FROM books LIMIT ?, '.$count.'\'';
		$sql_e = 'EXECUTE STMT USING @offset';
		
		
		$cmd_i = Yii::app()->db->createCommand($sql_i);
		$cmd_p = Yii::app()->db->createCommand($sql_p);
		$cmd_e = Yii::app()->db->createCommand($sql_e);
		$cmd_i->execute();
		$cmd_p->execute();
		
		$books = $cmd_e->queryAll();
		shuffle($books);
		$books = array_slice($books,0,$limit);
		
		$dataProvider=new CArrayDataProvider($books, array(
		  'id'=>'book',
		));
		
		return $dataProvider;
	}
	
	public function getByIndex($idxs, $order)
	{
		$cmd = Yii::app()->db->createCommand()
				->select('id, title as value')
				->from('books')
				->order($order) // порядок сортировки возвращаемый Sphinx'ом (по реливантности)
				->where(array('in', 'id', $idxs));
		
		return $cmd->queryAll();
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Book the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
