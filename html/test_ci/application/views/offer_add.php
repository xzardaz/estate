<?php $set=isset($offer);?>
<div class="offer">
	<div class="imageWrap">
		<img src="<?=$set?$offer->photo:'';?>" alt="image" width="180" height="180"  />
	</div>
	<div class="ofrAttrs">
		<div class="ofrLoc">
			<div>
				<img src="/test_ci/imgs/icons/house.png" id="addOfrTypeIcon" />
				<select>
					<option>
						Flat
					</option>
					<option>
						Store
					</option>
					<option>
						Garage
					</option>
					<option>
						Field
					</option>
					<option>
						House
					</option>
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
</div>


<div id="map-canvas"/>

