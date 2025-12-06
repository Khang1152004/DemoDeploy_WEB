<h3 class="text-lg font-semibold text-slate-900 mb-4"><?= htmlspecialchars($job['tieu_de']) ?></h3>
<p><strong>Mức lương:</strong> <?= htmlspecialchars($job['muc_luong_khoang']) ?></p>
<p><strong>Mô tả công việc:</strong><br><?= nl2br(htmlspecialchars($job['mo_ta_cong_viec'])) ?></p>
<p><strong>Yêu cầu ứng viên:</strong><br><?= nl2br(htmlspecialchars($job['yeu_cau_ung_vien'])) ?></p>
<a class="inline-flex items-center px-3 py-2 rounded-lg bg-primary-500 text-white text-sm font-semibold hover:bg-primary-600" href="index.php?c=Job&a=apply&id=<?= (int)$job['ma_tin_tuyen_dung'] ?>">Ứng tuyển</a>
