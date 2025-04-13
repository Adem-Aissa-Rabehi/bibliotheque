<?php include 'SIde-barre.php'; ?>
<div class="container-fluid">
    <h1 class="mt-4">Historique des Actions</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Administrateur</th>
                <th>Action</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody id="logsTable">
            <!-- Les logs seront chargés dynamiquement -->
        </tbody>
    </table>
</div>
<script src="../assets/js/logs.js"></script>