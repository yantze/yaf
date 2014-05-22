<html>

    <head>
        <title><?php echo $this->eprint($this->title); ?></title>
    </head>

    <body>

        <?php if (is_array($this->news)): ?>

            <!-- A table of some books. -->
            <table>
                <tr>
                    <th>title</th>
                    <th>content</th>
                </tr>

                <?php foreach ($this->news as $key => $val): ?>
                    <tr>
                        <td><?php echo $this->eprint($val['title']); ?></td>
                        <td><?php echo $this->eprint($val['content']); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php foreach ($this->news as $key => $val): ?>
                    <tr>
                        <td><input name="title" type="text" value="<?php echo $this->eprint($val['title']); ?>" /></td>
                        <td><textarea name="content" placeholder="put news content"><?php echo $this->eprint($val['content']); ?></textarea></td>
                    </tr>
                <?php endforeach; ?>

            </table>

        <?php else: ?>

            <p>There are no books to display.</p>

        <?php endif; ?>

        <form action="news/save_news.php" name="add_news" id="add_news" method="POST">
            <input name="title" type="text" placeholder="put news title" /><br/>
            <textarea name="content" placeholder="put news content"></textarea><br/>
            <input type="submit" value="add_news" />
        </form>

    </body>
</html>
