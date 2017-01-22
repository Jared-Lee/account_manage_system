
<?php
	include 'config.php' ;
	include 'functions.php' ;
	include 'style.php' ;	/*TESTS*/
?>

<html>
<head><title>六妖資訊帳務管理系統</title></head>

<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<header>
		<?php echo $header_context; ?>
	</header>
	
	<?php
	//必須讓主選單的選擇優先於次選單的記憶變數，不然會被記憶變數搶走優先權
	if(isset($_REQUEST['main_choose']))
		$action_choose=$_REQUEST['main_choose'];
	else if(isset($_REQUEST['Which_Main_choose']))
		$action_choose=$_REQUEST['Which_Main_choose'];
	else
		$action_choose=0;
	
	if( $action_choose>0)
	{
		$Index_Style=2;
	//主體<aside>在此縮排
	echo "<aside class='Aside_Style_2'>";
		//主選單<div>在此縮排
		echo "<div class='Main_aside_Style_2 Main_aside'>";
	}
	else
	{		$Index_Style=1;
	//主體<aside>在此縮排
	echo "<aside class='Aside_Style_1'>";
		//主選單<div>在此縮排
		echo "<div class='Main_aside_Style_1 Main_aside'>";
	}
			//列出主選單
			if($action_choose==1)
				echo "	<div class='div_Main_asideR'>
							<button type=submit name=main_choose 
									class='btn_main btn_main_active' value=1 >";
			else
				echo "	<div class='div_Main_aside'>
							<button type=submit name=main_choose 
									class='btn_main btn_main_disable' value=1 >";
									
				echo "					建立<br>報價單
							</button>
						</div>";

				if($action_choose==2)
					echo "	<div class='div_Main_asideR'>
								<button type=submit name=main_choose 
										class='btn_main btn_main_active' value=2 >";
				else
					echo "	<div class='div_Main_aside'>
								<button type=submit name=main_choose 
										class='btn_main btn_main_disable' value=2 >";
										
					echo "					查詢<br>報價單
								</button>
							</div>";
				
				if($action_choose==3)
					echo "	<div class='div_Main_asideR'>
								<button type=submit name=main_choose 
										class='btn_main btn_main_active' value=3 >";
				else
					echo "	<div class='div_Main_aside'>
								<button type=submit name=main_choose 
										class='btn_main btn_main_disable' value=3 >";
										
					echo "					查詢<br>成交訂單
								</button>
							</div>";
	?>	
			<div class='div_Main_aside'>
				<button type=button 
						onclick=window.open('./settings/client.php?mode=modify') 
						class='btn_main btn_main_disable' >
						設定<br>資料
				</button>			
			</div>		
		</div>	
	<?php
		//次選單區：要存在才會出現這塊div
		if( $action_choose>0){
			Sub_Aside($action_choose);
		}
	?>		
	</aside>

	<?php
	//尚未動作時 article 寬度為90%
	if($action_choose<1)
		echo "<article class='article_Style_1'>";
	//有按下主選單按鈕後 article 寬度為80%
	else
		echo "<article class='article_Style_2'>";
	echo "<section>";
		//主畫面的四個 button 的動作
		if($_POST['main_choose']==1) {
			//main_create_quotation() ;	//偉安負責的工作項目
			
			//新版建立報價單入口
			main_search_way(1,1) ;
		}

		if($_POST['main_choose']==2) {
			main_search_way(2,1) ;
		}		

		if($_POST['main_choose']==3) {
			main_search_way(3,1) ;
		}				
//////////////----↓ 偉安工作區域 ↓----------------------------------------------------------------------------------------------------
		//在建立報價單，選擇了一個要建立報價單的客戶，引導到輸入產品數量單價的介面
		if(isset($_POST['btm_confirm_client'])) {
			quotation_products_list( $_POST['btm_confirm_client'] ) ;
		}

		//接收 報價單要報價的產品及數量與單價，存到每個客戶的 table
		$quotation_arr = array() ;
		if(isset($_POST['btm_sent_quotation_list'])) {	
			for ( $i=1 ; $i<=10 ; $i++ ) {
				$client = $_POST['create_quotation_client'] ;
				$product_tmp = $_POST['select_product'.$i] ;
				$count_tmp = $_POST['count'.$i] ;
				$price_tmp = $_POST['price'.$i] ;
				if($product_tmp=='null') {
					continue ;
				}
				else {
					array_push($quotation_arr,$product_tmp) ;
					array_push($quotation_arr,$count_tmp) ;
					array_push($quotation_arr,$price_tmp) ;
					//echo $product_tmp." ".$count_tmp." ".$price_tmp."<br/>" ;
				}
			}
			save_quotation( $client, $quotation_arr) ;
		}
		
		// 家睿：下面這個交給偉安寫，現在是空function					
		/*<<<按鈕在查詢報價單中>>>，輸入值為某個訂單/報價單，可修改這個訂單的客戶、增減物品種類(ps.刪物品種類，直接把該項的invaild標記為1比較快)、更改數量*/
		if(isset($_POST['btm_edit_quo'])) {	
			$qu_id = $_POST['btm_edit_quo'] ;
			edit_quotation($qu_id) ;
		}
		
//////////////----↑ 偉安工作區域 ↑----------------------------------------------------------------------------------------------------
		
		
//////////////----↓ 家睿工作區域 ↓----------------------------------------------------------------------------------------------------
		
		if(isset($_POST['srch_way_choose'])){			
			main_search_way($_POST['Which_Main_choose'],0);
		}

		//在報價單查詢的畫面，選擇了任何一個地點，要顯示該地點所有的 client
		if(isset($_POST['btn_city_to_customer'])) {
			echo_city_customer($_POST['btn_city_to_customer']) ;
		}
		
		//選擇一個要查詢年分月份後，依照日期列出報價單。
		if(isset($_POST['btn_date_to_quo'])) {
			echo_list_month_all_simple_quo($_POST['btn_date_to_quo']);
		}
		
		//選擇一個要查詢報價單的客戶後，依照日期列出報價單。
		if(  isset( $_POST['btn_list_simple_quo'] )  ) {
			echo_list_single_customer_simple_quo($_POST['btn_list_simple_quo']);
		}
		
		//選擇要查詢客戶的其中一個報價單的詳細內容後
		if(isset($_POST['btn_list_detail_quo'])) {	
			echo_detail_quotation($_POST['btn_list_detail_quo']) ;
		}
				
		/*列出特定物品的供應商詳細資料*/
		if(isset($_POST['btn_detail_customer'])) {	
			//給的是客戶id
			list_detail_cus_or_sup_info(1,$_POST['btn_detail_customer']) ;
		}
		if(isset($_POST['btn_detail_supplier'])) {	
			//給的是物品id
			list_detail_cus_or_sup_info(2,$_POST['btn_detail_supplier']) ;
		}
		
		/*輸入值為某個訂單/報價單，將其is_order屬性更改為報價單/訂單，如果訂單流水號是空的，要產生新的訂單流水號*/
		if(isset($_POST['btm_order_change'])) {	
			order_change($_POST['btm_order_change']) ;
		}
		
		//建立 pdf
		if(isset($_POST['create_pdf'])) {
			$client = $_POST['quotations_client'] ;
			$index = $_POST['quotation_index'] ;
			create_quotation_pdf( $client, $index ) ;
		}

		
//////////////----↑ 家睿工作區域 ↑----------------------------------------------------------------------------------------------------

	?>
	</section>
	</article>
	<footer>
			<?php echo $footer_context; ?>
	</footer>
</form>
</body>
</html>

