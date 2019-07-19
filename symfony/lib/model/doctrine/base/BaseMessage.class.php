<?php

/**
 * BaseMessage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $project_id
 * @property integer $sender_id
 * @property integer $receiver_id
 * @property datetime $date
 * @property string $subject
 * @property string $message
 * @property User $Receiver
 * @property User $Sender
 * 
 * @method integer  getProjectId()   Returns the current record's "project_id" value
 * @method integer  getSenderId()    Returns the current record's "sender_id" value
 * @method integer  getReceiverId()  Returns the current record's "receiver_id" value
 * @method datetime getDate()        Returns the current record's "date" value
 * @method string   getSubject()     Returns the current record's "subject" value
 * @method string   getMessage()     Returns the current record's "message" value
 * @method User     getReceiver()    Returns the current record's "Receiver" value
 * @method User     getSender()      Returns the current record's "Sender" value
 * @method Message  setProjectId()   Sets the current record's "project_id" value
 * @method Message  setSenderId()    Sets the current record's "sender_id" value
 * @method Message  setReceiverId()  Sets the current record's "receiver_id" value
 * @method Message  setDate()        Sets the current record's "date" value
 * @method Message  setSubject()     Sets the current record's "subject" value
 * @method Message  setMessage()     Sets the current record's "message" value
 * @method Message  setReceiver()    Sets the current record's "Receiver" value
 * @method Message  setSender()      Sets the current record's "Sender" value
 * 
 * @package    letsonline
 * @subpackage model
 * @author     Rein Baarsma <solidwebcode@googlemail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseMessage extends myDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('message');
        $this->hasColumn('project_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('sender_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('receiver_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('date', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('subject', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('message', 'string', 4000, array(
             'type' => 'string',
             'length' => 4000,
             ));


        $this->index('receiver_index', array(
             'fields' => 
             array(
              0 => 'project_id',
              1 => 'receiver_id',
             ),
             ));
        $this->index('sender_index', array(
             'fields' => 
             array(
              0 => 'project_id',
              1 => 'sender_id',
             ),
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User as Receiver', array(
             'local' => 'receiver_id',
             'foreign' => 'id'));

        $this->hasOne('User as Sender', array(
             'local' => 'sender_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}