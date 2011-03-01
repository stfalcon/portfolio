<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<h4>Categories list</h4>

<?php if ($categories): ?>
    <ul>
        <?php foreach ($categories as $category): ?>
            <li>
                <a href="<?php echo $view['router']->generate('portfolioCategoryView', array('id' => $category->getId())) ?>">
                    <?php echo $category->getName(); ?>
                </a>
                <?php if ($view['security']->vote('ROLE_ADMIN')): ?>
                    (<a href="<?php echo $view['router']->generate('portfolioCategoryEdit', array('id' => $category->getId())) ?>">Edit Category</a>
                    / <a href="<?php echo $view['router']->generate('portfolioCategoryDelete', array('id' => $category->getId())) ?>">Delete Category</a>)
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <div class="info">
        List of categories is empty
    </div>
<?php endif; ?>
