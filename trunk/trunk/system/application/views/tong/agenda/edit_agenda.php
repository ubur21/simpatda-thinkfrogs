<?php 

?>
<link href="<?php echo base_url()?>assets/agenda/css/main.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url()?>assets/agenda/css/dp.css" rel="stylesheet" />    
<link href="<?php echo base_url()?>assets/agenda/css/dropdown.css" rel="stylesheet" />    
<link href="<?php echo base_url()?>assets/agenda/css/colorselect.css" rel="stylesheet" />   

<script src="<?php echo base_url()?>assets/agenda/jquery.js" type="text/javascript"></script>    
<script src="<?php echo base_url()?>assets/agenda/Plugins/Common.js" type="text/javascript"></script>        
<script src="<?php echo base_url()?>assets/agenda/Plugins/jquery.form.js" type="text/javascript"></script>     
<script src="<?php echo base_url()?>assets/agenda/Plugins/jquery.validate.js" type="text/javascript"></script>     
<script src="<?php echo base_url()?>assets/agenda/Plugins/datepicker_lang_US.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/agenda/Plugins/jquery.datepicker.js" type="text/javascript"></script>     
<script src="<?php echo base_url()?>assets/agenda/Plugins/jquery.dropdown.js" type="text/javascript"></script>     
<script src="<?php echo base_url()?>assets/agenda/Plugins/jquery.colorselect.js" type="text/javascript"></script>  

    <script type="text/javascript">
        if (!DateAdd || typeof (DateDiff) != "function") {
            var DateAdd = function(interval, number, idate) {
                number = parseInt(number);
                var date;
                if (typeof (idate) == "string") {
                    date = idate.split(/\D/);
                    eval("var date = new Date(" + date.join(",") + ")");
                }
                if (typeof (idate) == "object") {
                    date = new Date(idate.toString());
                }
                switch (interval) {
                    case "y": date.setFullYear(date.getFullYear() + number); break;
                    case "m": date.setMonth(date.getMonth() + number); break;
                    case "d": date.setDate(date.getDate() + number); break;
                    case "w": date.setDate(date.getDate() + 7 * number); break;
                    case "h": date.setHours(date.getHours() + number); break;
                    case "n": date.setMinutes(date.getMinutes() + number); break;
                    case "s": date.setSeconds(date.getSeconds() + number); break;
                    case "l": date.setMilliseconds(date.getMilliseconds() + number); break;
                }
                return date;
            }
        }
        function getHM(date)
        {
             var hour =date.getHours();
             var minute= date.getMinutes();
             var ret= (hour>9?hour:"0"+hour)+":"+(minute>9?minute:"0"+minute) ;
             return ret;
        }
        $(document).ready(function() {
            //debugger;
            var DATA_FEED_URL = "<?php echo site_url('agenda/datafeed')?>";
            var arrT = [];
            var tt = "{0}:{1}";
            for (var i = 0; i < 24; i++) {
                arrT.push({ text: StrFormat(tt, [i >= 10 ? i : "0" + i, "00"]) }, { text: StrFormat(tt, [i >= 10 ? i : "0" + i, "30"]) });
            }
            $("#timezone").val(new Date().getTimezoneOffset()/60 * -1);
            $("#stparttime").dropdown({
                dropheight: 200,
                dropwidth:60,
                selectedchange: function() { },
                items: arrT
            });
            $("#etparttime").dropdown({
                dropheight: 200,
                dropwidth:60,
                selectedchange: function() { },
                items: arrT
            });
            var check = $("#IsAllDayEvent").click(function(e) {
                if (this.checked) {
                    $("#stparttime").val("00:00").hide();
                    $("#etparttime").val("00:00").hide();
                }
                else {
                    var d = new Date();
                    var p = 60 - d.getMinutes();
                    if (p > 30) p = p - 30;
                    d = DateAdd("n", p, d);
                    $("#stparttime").val(getHM(d)).show();
                    $("#etparttime").val(getHM(DateAdd("h", 1, d))).show();
                }
            });
            if (check[0].checked) {
                $("#stparttime").val("00:00").hide();
                $("#etparttime").val("00:00").hide();
            }
            $("#Savebtn").click(function() { $("#fmEdit").submit(); });
            $("#Closebtn").click(function() { CloseModelWindow(); });
            $("#Deletebtn").click(function() {
				
                 if (confirm("Data ini akan dihapus ?")) {  
                    var param = [{ "name": "calendarId", value: 8}];
					// + "?method=remove"
                    $.post(DATA_FEED_URL+'/remove',
                        param,
                        function(data){
                              if (data.IsSuccess) {
                                    alert(data.Msg); 
                                    CloseModelWindow(null,true);                            
                                }
                                else {
                                    alert("Error occurs.\r\n" + data.Msg);
                                }
                        }
                    ,"json");
                }
            });
            
           $("#stpartdate,#etpartdate").datepicker({ picker: "<button class='calpick'></button>"});    
            var cv =$("#colorvalue").val() ;
            if(cv=="")
            {
                cv="-1";
            }
            $("#calendarcolor").colorselect({ title: "Color", index: cv, hiddenid: "colorvalue" });
            //to define parameters of ajaxform
            var options = {
                beforeSubmit: function() {
                    return true;
                },
                dataType: "json",
                success: function(data) {
                    alert(data.Msg);
                    if (data.IsSuccess) {
                        CloseModelWindow(null,true);  
                    }
                }
            };
            $.validator.addMethod("date", function(value, element) {                             
                var arrs = value.split(i18n.datepicker.dateformat.separator);
                var year = arrs[i18n.datepicker.dateformat.year_index];
                var month = arrs[i18n.datepicker.dateformat.month_index];
                var day = arrs[i18n.datepicker.dateformat.day_index];
                var standvalue = [year,month,day].join("-");
                return this.optional(element) || /^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1,3-9]|1[0-2])[\/\-\.](?:29|30))(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1,3,5,7,8]|1[02])[\/\-\.]31)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])[\/\-\.]0?2[\/\-\.]29)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:16|[2468][048]|[3579][26])00[\/\-\.]0?2[\/\-\.]29)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1-9]|1[0-2])[\/\-\.](?:0?[1-9]|1\d|2[0-8]))(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?:\d{1,3})?)?$/.test(standvalue);
            }, "Salah format tanggal !");
            $.validator.addMethod("time", function(value, element) {
                return this.optional(element) || /^([0-1]?[0-9]|2[0-3]):([0-5][0-9])$/.test(value);
            }, "Salah format waktu !");
            $.validator.addMethod("safe", function(value, element) {
                return this.optional(element) || /^[^$\<\>]+$/.test(value);
            }, "$<> tidak diijinkan");
            $("#fmEdit").validate({
                submitHandler: function(form) { $("#fmEdit").ajaxSubmit(options); },
                errorElement: "div",
                errorClass: "cusErrorPanel",
                errorPlacement: function(error, element) {
                    showerror(error, element);
                }
            });
            function showerror(error, target) {
                var pos = target.position();
                var height = target.height();
                var newpos = { left: pos.left, top: pos.top + height + 2 }
                var form = $("#fmEdit");             
                error.appendTo(form).css(newpos);
            }
        });
    </script>      
	
    <div>  
      <div class="toolBotton">           
        <a id="Savebtn" class="imgbtn" href="javascript:void(0);">
          <span class="Save"  title="Save the calendar">Simpan
          </span>          
        </a>                           
        <?php if(isset($event)){ ?>
        <a id="Deletebtn" class="imgbtn" href="javascript:void(0);">
          <span class="Delete" title="Cancel the calendar">Hapus
          </span>               
        </a>             
        <?php } ?>
        <a id="Closebtn" class="imgbtn" href="javascript:void(0);">
          <span class="Close" title="Close the window" >Tutup
          </span></a>            
        </a>        
      </div>                  
      <div style="clear: both">         
      </div>        
      <div class="infocontainer">            
        <form action="<?php echo site_url('agenda/datafeed')?>/adddetails<?php echo isset($event) ? '/'.$event->ID_AGENDA : ""; ?>" class="fform" id="fmEdit" method="post">
          <label>                    
            <span>*Subjek:</span>
            <div id="calendarcolor">
            </div>
            <input MaxLength="200" class="required safe" id="Subject" name="Subject" style="width:85%;" type="text" value="<?php echo isset($event) ? $event->SUBJECT:"" ?>" />
            <input id="colorvalue" name="colorvalue" type="hidden" value="<?php echo isset($event) ? $event->WARNA_AGENDA : "" ?>" />
          </label>                 
          <label>                    
            <span><!--*Time:-->*Tanggal & Jam</span>
            <div>  
              <?php 
			  if(isset($event)){
                  $arr = date_parse($event->TGL_AWAL);
				  $tmp = mktime($arr["hour"],$arr["minute"],$arr["second"],$arr["month"],$arr["day"],$arr["year"]);
				  $sarr = explode(" ", date("m/d/Y H:i", $tmp) );
				  
                  $arr = date_parse($event->TGL_AKHIR);
				  $tmp = mktime($arr["hour"],$arr["minute"],$arr["second"],$arr["month"],$arr["day"],$arr["year"]);
                  $earr = explode(" ", date("m/d/Y H:i", $tmp) );
              }
			  ?>                    
              <input MaxLength="10" class="required date" id="stpartdate" name="stpartdate" style="padding-left:2px;width:90px;" type="text" value="<?php echo isset($event) ? $sarr[0] : ""; ?>" />
              <input MaxLength="5" class="required time" id="stparttime" name="stparttime" style="width:40px;" type="text" value="<?php echo isset($event) ? $sarr[1] : ""; ?>" />To
              <input MaxLength="10" class="required date" id="etpartdate" name="etpartdate" style="padding-left:2px;width:90px;" type="text" value="<?php echo isset($event)?$earr[0]:""; ?>" />
              <input MaxLength="50" class="required time" id="etparttime" name="etparttime" style="width:40px;" type="text" value="<?php echo isset($event)?$earr[1]:""; ?>" />
              <label class="checkp"> 
                <input id="IsAllDayEvent" name="IsAllDayEvent" type="checkbox" value="1" <?php if(isset($event) && $event->IS_ALLDAYEVENT!=0) {echo "checked";} ?>/><!--All Day Event-->Sepanjang Hari
              </label>                    
            </div>                
          </label>                 
          <label>                    
            <span>Lokasi:</span>                    
            <input MaxLength="200" id="Location" name="Location" style="width:95%;" type="text" value="<?php echo isset($event)?$event->LOKASI:""; ?>" />
          </label>                 
          <label>                    
            <span>Perihal</span>
			<textarea cols="20" id="Description" name="Description" rows="2" style="width:95%; height:70px"><?php echo isset($event) ? $event->DESKRIPSI : ""; ?></textarea>
          </label>
		  <label>
			<span>Status:</span>
			<select name='status' id='status'>
				<option value='0' <?php echo (isset($event) && $event->STATUS==0) ? 'selected' : ''?>>Rencana</option>
				<option value='1' <?php echo (isset($event) && $event->STATUS==1) ? 'selected' : ''?>>Terlaksana</option>
				<option value='2' <?php echo (isset($event) && $event->STATUS==2) ? 'selected' : ''?>>Tertunda</option>
				<option value='3' <?php echo (isset($event) && $event->STATUS==3) ? 'selected' : ''?>>Dibatalkan</option>
			</select>
		  </label>
          <input id="timezone" name="timezone" type="hidden" value="" />           
        </form>         
      </div>         
    </div>