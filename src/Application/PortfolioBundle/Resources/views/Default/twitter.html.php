<ul>
    <?php foreach ($statuses as $status): ?>
        <li>
        <?php echo $status->text; ?>
        <em><?php echo $status->created_at; ?></em>
    </li>
    <?php endforeach; ?>
</ul>
