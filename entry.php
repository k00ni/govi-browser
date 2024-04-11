<?php
require 'vendor/autoload.php';
require 'header.php';

$iri = substr($_GET['iri'], 0, 2000);
$iri = urldecode($iri);
$entry = getEntryFromIndex($iri);
?>
<div class="container mt-2">
    <a class="btn btn-outline-primary btn-sm" href="/">back</a>
    <hr class="mt-3">

    <div class="row">
        <div class="col"><h2><?php echo $entry[0]; ?></h2></div>
        <div class="col text-end"><strong>Last access:</strong> <?php echo $entry[6]; ?></div>
    </div>

    <div><strong>IRI:</strong> <?php echo $entry[1]; ?></div>
    <div><strong>Data Source:</strong> <a href="<?php echo $entry[8]; ?>"><?php echo $entry[7]; ?></a></div>

    <div class="alert alert-light mt-4">
        <strong>Description:</strong> Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
    </div>

    <nav>
        <div class="nav nav-tabs mt-5" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Home</button>
            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</button>
        </div>
    </nav>
        <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">...</div>
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">...</div>
    </div>
</div>

<?php
require 'footer.php';