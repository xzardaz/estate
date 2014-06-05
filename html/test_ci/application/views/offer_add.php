<?php $set=isset($offer);?>
<div class="offer">
	<div class="imageWrap">
		<img id="addOfrFrontImg" src="<?=$set?$offer->photo:'';?>" alt="image" width="180" height="180"  />
	</div>
	<div class="ofrAttrs">
		<div class="ofrLoc">
			<div>
				<select class="ofrTypeSelect">
					<?php
						foreach($types as $type) 
						{
							echo "
								<option style='background:url(\"".$type['icon']."\") no-repeat'>
									 ".$type['name']."
								</option>";
						}; ?>
				</select>
				in
				 location here
			</div>
		</div>
		<div class="ofrBrief">
				<textarea>
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
						m2
					</div>
				</div>
				<div class="ofrAgencyLogo">
					<img src="http://img01.imovelweb.com.br//logos/755474_imovelweblogo.jpg" alt="company" height="50" width="150" />
				</div>
			</div>
		</div>
	</div>
	
	<input type='file' id='uplImgsId' multiple/>
	<div id='imgsin'>
	</div>
</div>


<div id="map-canvas"/>

