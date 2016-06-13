<?php
namespace Admin\Model;
use Think\Model;
class MeetingMinuteModel extends Model {
	protected $tableName = "meeting_minute";
	
	// 获取所有的业务
	public function getAllMeetingMinute(){
		return $this->field('id,pid,aid,meeting_topic,address,meeting_noter,meeting_organizers,executor,content,remark,start_time,end_time,status')->order('id asc')->select();
	}
	
}

?>