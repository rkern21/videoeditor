<div class="cart_quickcart">
    <?php if ($this->checkout_mode == 'sandbox'):?><span class="attention"><?php echo JText::_('ROKQUICKCART.WARN.SANDBOX_MODE')?></span><?php endif;?>
    <div class="cart_cartstatus">
        <div class="cart_statusicon"></div>
            <a href="#rokquickcart"><?php echo JText::_('ROKQUICKCART.YOUR_CART');?>: (<span class="simpleCart_quantity"></span> <?php echo JText::_('ROKQUICKCART.ITEMS');?>)</a>
    </div>
    <h2><?php echo $this->page_title;?></h2>
    <div class="cart_products">
	<?php foreach($this->items as $item): ?>
		<div class="simpleCart_shelfItem">
		 	<div class="cart_product_grad"></div>
    	    <div class="cart_product_sur1"></div>
    	    <div class="cart_product_sur2"></div>
    	    <div class="cart_product_sur3"></div>
    	    <div class="cart_product_sur4"></div>
    	    <div class="cart_product_content">
    	   		<div class="cart_padding">
	    	   		<div class="cart_product_l">
	    	   			<?php if($this->use_rokbox):?><a rel="rokbox" href="<?php echo $item->getFullImage();?>"><?php endif; ?>
	        	        <img src="<?php echo $item->getShelfImage();?>" alt="<?php echo $item->name;?>" title="<?php echo $item->name;?>" height="<?php echo $item->getShelfImageHeight(); ?>" width="<?php echo $item->getShelfImageWidth(); ?>" class="item_image"/>
	        	        <?php if($this->use_rokbox):?></a><?php endif; ?>
	        	        <span class="item_price"><?php echo $this->currency_symbol;?><?php echo $item->price;?></span>
	           		</div>
					<div class="cart_product_r">
	            		<h5 class="item_name"><?php echo $item->name;?></h5>
	            		<p class="product_Description">
							<?php echo $item->description;?>
						</p>
	            		<?php if($item->show_sizes):?><div><?php echo JText::_('ROKQUICKCART.SIZE');?>: <?php echo $item->sizes;?></div><?php endif;?>
	            		<?php if( $item->show_colors):?><div><?php echo JText::_('ROKQUICKCART.COLOR');?>: <?php echo $item->colors;?></div><?php endif;?>
	            		<?php if($this->shipping_per_item):?><div><input class="item_shipping" value="<?php echo $item->shipping;?>" type="hidden"></div><?php endif;?>
	            		<span class="item_thumb"><?php echo $item->getCartImage();?></span>
	            		<div class="cart_product_add">
	            		    <a href="#rokquickcart" class="readon item_add"><span><?php echo JText::_('ROKQUICKCART.ADD_TO_CART');?></span></a>
	            		</div>
	            	</div>
            	</div>
            </div>
		</div>
	<?php endforeach; ?>
	</div>
    <div class="clr"></div>
    <a id="rokquickcart"></a>
    <div class="title_yourcart"><?php echo JText::_('ROKQUICKCART.YOUR_CART');?>: (<span class="simpleCart_quantity"></span> <?php echo JText::_('ROKQUICKCART.ITEMS');?>)</div>
    <div class="cart_yourcart">
    	<div class="cart_grad"></div>
	    <div class="cart_sur1"></div>
	    <div class="cart_sur2"></div>
	    <div class="cart_sur3"></div>
	    <div class="cart_sur4"></div>
	    <div class="cart_yourcart_items">
            <div class="simpleCart_items" >
            </div>
            <div class="cart_totals">
                <div><?php echo JText::_('ROKQUICKCART.SUB_TOTAL');?>: <span class="simpleCart_total"></span></div>
                <?php if($this->tax):?><div><?php echo JText::_('ROKQUICKCART.TAX');?>: <span class="simpleCart_taxCost"></span></div><?php endif;?>
                <?php if($this->shipping):?><div><?php echo JText::_('ROKQUICKCART.SHIPPING');?>: <span class="simpleCart_shippingCost"></span></div><?php endif;?>
                <div><?php echo JText::_('ROKQUICKCART.TOTAL');?>: <span class="simpleCart_finalTotal"></span></div>
            </div>
            <div class="clr"></div>
        </div>
    </div>
    <div class="cart_buttons">
        <a href="javascript:;" class="simpleCart_checkout readon"><span><?php echo JText::_('ROKQUICKCART.CHECKOUT');?></span></a>
        <a href="javascript:;" class="simpleCart_empty readon"><span><?php echo JText::_('ROKQUICKCART.EMPTY_CART');?></span></a>
    </div>
    <div class="clr"></div>
</div>
