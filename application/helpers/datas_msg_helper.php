<?php
defined('BASEPATH') OR exit('No direct script access allowed');


if ( ! function_exists('output_msg'))
{
	function output_msg($key = '0000')
	{
		$msg = array(
				'0000' => '?',

				'form_01' => '新增',				// from : 表單
				'form_02' => '編輯',
				'form_03' => '刪除',
				'form_21' => '新增成功',
				'form_22' => '編輯成功',
				'form_25' => '編輯發生錯誤',
				'form_35' => '資料的類型不正確!',
				'form_50' => '發生錯誤',
				'form_51' => 'Insert',
				'form_52' => 'Edit',
				'form_53' => 'Delete',

				'register_01' => '註冊成功',
				'register_02' => '註冊失敗',
				'register_03' => '社群註冊失敗',

				'login_01' => '登錄成功',
				'login_02' => '查無此帳號',
				'login_03' => '密碼輸入錯誤',
				'login_04' => '您無權限登錄',
				'login_05' => '此Token已過期,請重新登入',
				'login_06' => '此帳號已被註冊',
				'login_07' => '帳號或密碼不正確',
				'login_08' => '儲存push_token時發生錯誤',

				'signup_01' => '帳號不可為空',
				'signup_02' => '密碼不可為空',
				'signup_03' => '兩次輸入的密碼不相同',

				'info_01' => '密碼變更成功',  	// info : 基本資訊(個人、會員等...)

				'msg_01' => 'success',  		// msg : ajax or api
				'msg_02' => 'error',
				'msg_11' => '資料取得成功',
				'msg_12' => '資料取得失敗',

				'priv_02' => '此使用者無權限使用此資源',

				// For http status message used
				'200' => 'success',
				'201' => '新增成功',
				'304' => '請求的資源未被修改',
				'400' => '客戶端使用無效的請求',
				'401' => '此客戶端尚未被驗證',
				'403' => '此客戶端是被禁止使用此請求',
				'404' => '請求的資源不存在',
				'405' => '請求的方法不支援',
				'406' => '標頭檔不支援',
				'500' => '伺服器發生問題, 請聯絡工程師',

				// For Sales api project used
				'sales_01' => '社群註冊失敗,無法登入',
				'sales_02' => '此email已經由其他社群帳號或註冊綁定,請登入使用此email註冊之帳號進行綁定再登入。',
				'sales_03' => '目標年或月不符合規範,或是目標名稱已存在',
				'sales_04' => '目標資源不屬於此使用者,或者查無結果',
				'sales_05' => '請輸入目標資源編號',
				'sales_06' => '購買成功',
				'sales_07' => '購買失敗',
		);

		return $msg[$key];
	}
}