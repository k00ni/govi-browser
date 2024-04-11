<div class="container">

    <div class="row">
        <div class="col-3">
            <h5>Data sources</h5>
            <ul>
                <?php
                foreach (getDataSources() as $ds) {
                    echo '<li>'.$ds['name'].'</li>';
                }
                ?>
            </ul>

        </div>
        <div class="col-9">
            <?php
            foreach (loadIndexIntoArray(30) as $row) {
                $entryLink = 'entry.php?iri='.urlencode($row[1]);

                echo '<div class="p-3 mb-3 border-bottom">
                    <h4><a href="'.$entryLink.'">'.$row[0].'</a></h4>
                    <div><strong>IRI:</strong> '.$row[1].'</div>
                    <div><strong>Last access:</strong> '.$row[6].'</div>
                </div>';
            }
            ?>
        </div>
    </div>

</div>