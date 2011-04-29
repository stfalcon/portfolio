<ul>
    <?php foreach ($statuses as $status): ?>
        <li>
        <?php echo $status->text; ?>
        <em><?php echo $status->time; ?></em>
    </li>
    <?php endforeach; ?>
</ul>
