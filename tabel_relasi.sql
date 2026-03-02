-- Tambah kolom id_user di tabel orang_tua
ALTER TABLE orang_tua ADD COLUMN id_user INT AFTER email;

-- Update data contoh
UPDATE orang_tua SET id_user = 1 WHERE id_ortu = 'ORT001';

-- Tambah data kelas contoh
INSERT INTO kelas (id_kelas, nama_kelas, tingkat, id_guru) VALUES
('KLS001', 'TK A', 'A', 'GR001'),
('KLS002', 'TK B', 'B', 'GR001');

-- Tambah data siswa contoh
INSERT INTO siswa (id_siswa, nis, nama_siswa, jk, id_kelas, status) VALUES
('SW001', '2024001', 'Ahmad Budi', 'L', 'KLS001', 'Aktif'),
('SW002', '2024002', 'Siti Aminah', 'P', 'KLS001', 'Aktif');