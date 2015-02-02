<div id="contentDivbt"></div>
<div id="contentiDiv">
    <?php print $breadcrumb; // Выводим "Выводим "хлебне крошки"  ?>
    <?php print $messages; // Выводим сообщения системы  ?>
    <!-- Заголовок с суффиксом и преффиксом -->
    <?php print render($title_suffix); ?>
    <?php if($title): ?> <h1 class="pageTitle"><?php print $title; ?></h1><?php endif; ?>
    <?php print render($title_suffix); ?>
    <?php print render($tabs); // Выводим табы  ?> 
    <?php print render($page['content']); // Выводим регион "контент"  ?>

<?php /*    <h1>First page news Block</h1>
    <textarea>
    <?php print_r( $page['firstpagenews'] ); ?>

    </textarea>
<?php print render($page['firstpagenews']); ?> */ ?>
</div>
<div id="contentDivbb"></div>