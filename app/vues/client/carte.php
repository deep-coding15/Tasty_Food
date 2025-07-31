<div class="carts grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">
    <?php foreach ($plats as $plat): ?>
        <div class="max-w-sm max-h-full pb-8 bg-white rounded-2xl shadow-lg hover:scale-105 transition">
            <img src="<?= htmlspecialchars($plat->getImgPlat()) ?>" 
                 alt="<?= 'image du plat ' . htmlspecialchars($plat->getNomPlat()) ?>"
                 class="w-full h-48 object-cover rounded-t-2xl">
            <div class="p-6 space-y-4 min-h-fit">
                <p class="text-xl font-bold text-gray-800"><?= htmlspecialchars($plat->getNomPlat()) ?></p>
                <p><?= number_format($plat->getPrixPlat(), 2) ?> DH</p>
                <div class="flex items-center justify-between h-full">
                    <span class="text-lg font-semibold text-green-600">
                        <?= number_format($plat->getPrixPlat(), 2) ?> DH
                    </span>
                    <a href="?page=<?= urlencode($page) ?>&id=<?= $plat->getIdPlat() ?>#plat"
                       class="bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition hover:bg-blue-700">
                        See profile
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
