<section class="bg-light py-5">
    <div class="container">
        <div class="card shadow p-4 mx-auto" style="max-width: 700px;">
            <h2 class="text-center mb-4">Consultation du rapport</h2>

            <p><strong>Praticien :</strong> <?= htmlspecialchars($rapport['PRA_NUM']) ?></p>
            <p><strong>Date de visite :</strong> <?= htmlspecialchars($rapport['RAP_DATEVISITE']) ?></p>
            <p><strong>Motif :</strong> <?= htmlspecialchars($rapport['RAP_MOTIF']) ?></p>
            <p><strong>Contenu du rapport :</strong><br><?= nl2br(htmlspecialchars($rapport['RAP_BILAN'])) ?></p>
            <p><strong>État :</strong> <?= htmlspecialchars($rapport['RAP_ETAT']) ?></p>

            <div class="text-center mt-4">
                <a href="index.php?uc=rapportVisite&action=liste" class="btn btn-secondary">Retour à la liste</a>
            </div>
        </div>
    </div>
</section>
