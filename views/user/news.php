<?php 
$this->title = 'Новости';
?>
<div class="tab-cont active">
    <div class="padd-block">
        <div class="heading">Новости</div>
        <div class="tabs secondary">
            <div class="tab-block">
            </div>
        </div>
    </div>
    <div class="tab-content secondary">
        <div class="tab-cont active">
            <div class="padd-block">
                <?php foreach($news as $item): ?>
                <div class="news-block">
                    <div class="title"><?php echo $item->title; ?></div>
                    <div class="desc"><?php echo $item->desc_text; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
