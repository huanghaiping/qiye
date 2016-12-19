<?php
/**
 * 
 * 回调基础类
 * @author widyhu
 *
 */
class WxPayNotify extends WxPayNotifyReply
{
	/**
	 * 
	 * 回调入口
	 * @param bool $needSign  是否需要签名输出
	 */
	final public function Handle($needSign = true)
	{
		$msg = "OK";
		//当返回false的时候，表示notify中调用NotifyCallBack回调失败获取签名校验失败，此时直接回复失败
		$result = WxpayApi::notify(array($this, 'NotifyCallBack'), $msg);
		if($result == false){
			$this->SetReturn_code("FAIL");
			$this->SetReturn_msg($msg);
			return $this->ReplyNotify($needSign);
		} else {
			//该分支在成功回调到NotifyCallBack方法，处理完成之后流程
			$this->SetReturn_code("SUCCESS");
			$this->SetReturn_msg("OK");
			return $this->ReplyNotify($needSign);
		}
		
	}

	
	/**
	 * 
	 * notify回调方法，该方法中需要赋值需要输出的参数,不可重写
	 * @param array $data
	 * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
	 */
	final public function NotifyCallBack($data)
	{
		  
		$msg = "OK";
		$result = $this->NotifyProcess($data, $msg);
		if($result == true){
			$this->SetReturn_code("SUCCESS");
			$this->SetReturn_msg("OK");
			$this->SetOut_trade_no($data['out_trade_no']);
		} else {
			$this->SetReturn_code("FAIL");
			$this->SetReturn_msg($msg);
		}
		return $result;
	}
	
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		//LogClass::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg) 
	{
		//LogClass::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		 
		return true;
	}
	
	/**
	 * 
	 * 回复通知
	 * @param bool $needSign 是否需要签名输出
	 */
	final private function ReplyNotify($needSign = true)
	{
		//如果需要签名
		$success= $this->GetReturn_code($return_code);
		if($needSign == true && $success== "SUCCESS")
		{ 
			$this->SetSign();
		}
		$GetOut_trade_no=$this->GetOut_trade_no(); 
		return array("status"=>$success== "SUCCESS" ? 1 : 0,"order_id"=>$GetOut_trade_no,'trade_status'=>$this->GetReturn_msg(),"xml"=>$this->ToXml());
	}
}