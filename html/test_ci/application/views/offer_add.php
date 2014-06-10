<?php $set=isset($offer);?>
<div class="offer">
	<div class="imageWrap">
		<img id="addOfrFrontImg" src="<?=base_url()?>imgs/icons/qna.png" alt="image" width="180" height="180"  />
	</div>
	<div class="ofrAttrs">
		<div class="ofrLoc">
			<div>
				<select class="ofrTypeSelect">
					<?php
						foreach($types as $type) 
						{
							echo "
								<option>
									 ".$type['name']."
								</option>";
						}; ?>
				</select>
				<div id="ofrLocTxt">
				in
				 location here
				</div>
			</div>
		</div>
		<div class="ofrBrief">
				<textarea class="grayBoldText">
Type a brief description here...
				</textarea>
		</div>
		<div class="ofrProps">
			<div class="ofrBottom">
				<div class="ofrBottomProps">
					<div class="ofrCurrentProp">
						<b>$</b>
						<input type="text" id="inAddOfrPrice"/>
					</div>
					<div class="ofrCurrentProp">
						<input type="text" id="inAddOfrRooms"/>
						 rooms
					</div>
					<div class="ofrCurrentProp">
						<input type="text" id="inAddOfrArea"/>
						m&sup2
					</div>
				</div>
				<div class="ofrAgencyLogo">
					<img src="http://img01.imovelweb.com.br//logos/755474_imovelweblogo.jpg" alt="company" height="50" width="150" />
				</div>
			</div>
		</div>
	</div>
	
</div>
<!--
-->
<h4>
	Photos
</h4>
<input type='file' id='uplImgsId' multiple/>
<div id='imgsin'>
</div>
<br/>

<h4>
	Location
</h4>
<div id="map-canvas">
</div>
<br/>

<h4>
	Video
</h4>
<div class="addVidControls">
	<form action="#">
		<input type="text"   id="addVidTxtBoxId" value="Paste YouTube url" class="grayBoldText"/>
		<input type="submit" id="addVidBtnId"    value="Add video"/>
	</form>
</div>
<div id="addVidWrapper"/>
</div>
<!--
<object style="visibility: visible;" data="http://www.youtube.com/v/pNt0iVG2VOA?enablejsapi=1&;playerapiid=addVidWrapper&;version=3" id="addVidWrapper" type="application/x-shockwave-flash" height="356" width="680"><param value="always" name="allowScriptAccess"></object>
-->

