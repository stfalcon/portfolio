<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<h2>Projects list</h2>

<?php if ($projects): ?>
    <table>
    <?php foreach ($projects as $project): ?>
        <tr>
            <td>
                <?php echo $project['name']; ?>
            </td>
            <td>
                <?php echo $project['description']; ?>
            </td>
            <td>
                <a href="<?php echo $view['router']->generate('portfolioProjectEdit', array('id' => $project['id'])) ?>">Edit project</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>
