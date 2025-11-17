-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 17 Kas 2025, 18:26:19
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `haber_portali`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `fullname`, `created_at`) VALUES
(1, 'admin', '$2y$10$k88CM2h2fECPCmGOnDLXvupGVVyNCP2qMJNy5mJj0LhFESNZVHdFe', 'Site Yöneticisi', '2025-11-16 13:11:56');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `haberler`
--

CREATE TABLE `haberler` (
  `id` int(10) UNSIGNED NOT NULL,
  `kategori` varchar(50) DEFAULT 'Genel',
  `baslik` varchar(255) NOT NULL,
  `icerik` text NOT NULL,
  `resim` varchar(255) DEFAULT NULL,
  `editor_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `tarih` datetime DEFAULT current_timestamp(),
  `editor_adi` varchar(100) DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `haberler`
--

INSERT INTO `haberler` (`id`, `kategori`, `baslik`, `icerik`, `resim`, `editor_id`, `created_at`, `tarih`, `editor_adi`) VALUES
(9, 'Magazin', 'Nilperi Şahinkaya’dan Gece Yarısı Aşk Paylaşımı', 'Oyuncu Nilperi Şahinkaya, yeni sevgilisi Baturalp Bekar ile gece saat 04:58’de romantik bir poz paylaştı. “Aşığım” notuyla yaptığı paylaşım, sosyal medyada büyük ilgi topladı. Şahinkaya, eski partneri Emre Yusufi’den sonra ilişkisini gözler önüne sermiş oldu.', 'https://iatkv.tmgrup.com.tr/d691bc/616/321/0/36/655/378?u=https%3a%2f%2fitkv.tmgrup.com.tr%2f2025%2f11%2f14%2fsaat-0458de-gelen-surpriz-nilperi-sahinkayadan-cok-konusulan-ask-pozu-1763100722261.jpg', 1, '2025-11-16 15:11:31', '2025-11-16 18:11:31', 'admin'),
(10, 'Teknoloji', 'Yapay Zekâ Günlük Hayatımızı Dönüştürüyor: Sağlıktan Finansa, Endüstriden Eğitime', 'Yapay zekâ, hızla gelişen algoritmalar ve büyük veri kullanımı sayesinde birçok sektörde devrim yaratıyor. Sağlık alanında erken teşhis sistemlerinden, endüstride otomatik üretim hatlarına; finans sektöründe risk analizinden, eğitimde kişiselleştirilmiş öğrenme platformlarına kadar yapay zekâ uygulamaları hayatın her alanına nüfuz ediyor.\r\n\r\nUzmanlar, yapay zekânın sunduğu verimlilik ve hız avantajlarının yanı sıra, veri güvenliği, etik ve iş gücü dönüşümü gibi konularda da dikkatli olunması gerektiğini belirtiyor. Günümüz teknolojisi, yalnızca süreçleri optimize etmekle kalmayıp, gelecekte toplumların iş yapış biçimini ve yaşam standartlarını köklü şekilde değiştirme potansiyeline sahip.', 'https://www.innova.com.tr/medias/yapay-zeka-ile-ilgili-3-buyuk-risk-ve-alinabilecek-onlemler.jpg', 1, '2025-11-16 16:17:37', '2025-11-16 19:17:37', 'admin'),
(11, 'Teknoloji', 'Yapay Zekâ: Verimlilik ve Etik Arasında Denge Arayışı', 'YZ uygulamaları, işletmelerin verimliliğini artırırken, sağlık ve eğitim alanında yenilikçi çözümler sunuyor. Bununla birlikte, algoritmik tarafsızlık, veri gizliliği ve iş gücü dönüşümü gibi konular, teknolojinin hızla yayılmasıyla birlikte tartışmaların merkezine yerleşiyor. Uzmanlar, bu dengeyi sağlamak için hem teknolojik hem de hukuki düzenlemelerin önemine dikkat çekiyor.', 'https://www.deryauluduz.com/wp-content/uploads/2024/05/yapay-zeka-ile-insan-beyni-arasindaki-cilgin-benzerlikler.jpg', 1, '2025-11-16 16:55:59', '2025-11-16 19:55:59', 'admin'),
(12, 'Spor', 'Türkiye A Millî Takımı Play‑off’u Garantiledi', 'Türkiye, 2026 FIFA Dünya Kupası Avrupa Elemeleri’nde Bulgaristan’ı 2-0 mağlup etti. Bu galibiyetle grup ikinciliği garantiye alındı ve milliler play-off’a kaldı.', 'https://www.tff.org/Resources/TFF/Auto/3da1ecc0db98445c8283e75629ad4793.jpg', 1, '2025-11-16 16:58:12', '2025-11-16 19:58:12', 'admin'),
(13, 'Spor', 'Belçika Milli Takımı Teknik Direktörü Görevden Alındı', 'Belçika Futbol Federasyonu, Domenico Tedesco’yu teknik direktörlük görevinden alındığını açıkladı. Karar, takımın istenilen performansı verememesi üzerine alındı.', 'https://a1.espncdn.com/combiner/i?img=%2Fphoto%2F2025%2F1116%2Fr1576301_1296x729_16%2D9.jpg&w=570&format=jpg', 1, '2025-11-16 16:59:14', '2025-11-16 19:59:14', 'admin'),
(14, 'Magazin', 'Usta Sanatçı Muazzez Abacı Hayatını Kaybetti', 'Türk klasik müziğinin önde gelen isimlerinden Muazzez Abacı, kalp krizi geçirmesinin ardından vefat etti. Abacı 78 yaşındaydı ve müzik dünyasında uzun yıllardır etkili bir figürdü.', 'https://image.hurimg.com/i/hurriyet/90/770x0/691999b1f252bbd347c3888a.jpg', 1, '2025-11-16 17:02:54', '2025-11-16 20:02:54', 'admin'),
(15, 'Son Dakika', 'Şanlıurfa\'da Çırak İşçiye Şiddet Uygulayan Kalfa Tutuklandı', 'Şanlıurfa\'nın Bozova ilçesindeki bir marangoz atölyesinde 15 yaşındaki çırak M.K.\'ya kompresörle şiddet uygulayan kalfa Habip Aksoy, gözaltına alındıktan sonra tutuklandı. Olay sonrası M.K., ağır yaralanarak hastaneye kaldırıldı ve hayati tehlikesinin sürdüğü bildirildi.\r\nŞANLIURFA\'nın Bozova ilçesindeki bir marangoz atölyesinde çırak olarak çalışan M.K.\'ya (15) kompresörle şiddet uygulayan kalfa Habip Aksoy, hakkında çıkan tutuklama kararı üzerine gözaltına alındı. Gaziantep\'e kaçmak isterken yakalanan Aksoy, sevk edildiği nöbetçi mahkemece tutuklandı.\r\n\r\nOlay, dün Bozova\'daki bir marangoz atölyesinde meydana geldi. İddiaya göre, atölyede çırak olarak çalışan M.K., kalfa olarak çalışan Habip Aksoy ile kimliği öğrenilemeyen bir arkadaşı tarafından henüz bilinmeyen nedenle önce elleri bağlanarak etkisiz hale getirildi. Ardından pantolonu zorla çıkarılan çocuğun makatına yüksek basınçlı kompresörle hava verildi. Ağır yaralanarak yere yığılan M.K.\'yı görenlerin ihbarı üzerine olay yerine sağlık ekipleri sevk edildi. İlk müdahalesi Bozova Mehmet Enver Yıldırım Devlet Hastanesi\'nde yapılan çocuk, daha sonra Balıklıgöl Devlet Hastanesi\'ne, ardından Harran Üniversitesi Araştırma ve Uygulama Hastanesi\'ne sevk edildi. İç organlarında ciddi hasar oluştuğu belirtilen Muhammed K., yoğun bakımda tedavi altına alındı.\r\n\r\nÖNCE SERBEST KALDI SONRA TUTUKLANDI', 'https://foto.haberler.com/haber/2025/11/16/sanliurfa-da-ciraga-kompresorle-siddet-uygula-19259728_amp.jpg', 1, '2025-11-16 17:07:15', '2025-11-16 20:07:15', 'Admin'),
(16, 'Ekonomi', 'Apple, 634 milyon dolar ceza ödeyecek', 'Kaliforniya Federal Jürisi, Apple\'ın tıbbi cihaz üreticisi Masimo\'ya kan oksijen izleme teknolojisi patentini ihlal ettiği için 634 milyon dolar ödemesi gerektiğine karar verdi.\r\nJüri, Apple Watch\'un egzersiz modu ve kalp atış hızı bildirim özelliklerinin Masimo\'nun patentini ihlal ettiğini tespit ettiğini açıkladı. Masimo tarafından yapılan açıklama \"Fikri mülkiyet haklarımızı ileriye dönük olarak savunmaya kararlıyız.\" şeklinde oldu.', 'https://geoim.bloomberght.com/l/2025/11/16/ver1763300655/3761618/jpg/640x360', 8, '2025-11-16 17:08:39', '2025-11-16 20:08:39', 'admin'),
(17, 'Son Dakika', 'İzmir\'de Uyuşturucu Operasyonu: İki Şüpheli Tutuklandı', 'Kemalpaşa\'da düzenlenen uyuşturucu operasyonunda 1572 gram sentetik uyuşturucu ve birçok kaçak malzeme ele geçirildi. Operasyonda M.K. ve E.İ. tutuklandı.\r\nİzmir\'in Kemalpaşa ilçesinde düzenlenen uyuşturucu operasyonunda yakalanan 3 şüpheliden 2\'si tutuklandı.\r\n\r\nKemalpaşa İlçe Emniyet Müdürlüğüne bağlı polis ekipleri ilçede uyuşturucu madde satıldığı bilgisine ulaştı.\r\n\r\nSuç Önleme ve Soruşturma Büro Amirliği ekipleri tarafından yapılan teknik ve fiziki takip sonucu M.K\'ye (26) ait Soğukpınar Mahallesi\'ndeki iş yeri ile aynı şahsa ait Örnekköy Mahallesi\'ndeki eve operasyon düzenlendi.\r\n\r\nAdreslerde yapılan aramalarda 1572 gram sentetik uyuşturucu, 4 gram esrar, 7 sentetik ecza maddesi, 3 hassas terazi, uyuşturucu madde yapımında kullanılan maddeler, 9 uyuşturucu düzeneği ile 2 kurusıkı tabanca, 28 tabanca fişeği ve uyuşturucu ticaretinden elde edildiği değerlendirilen 41 bin 700 lira ele geçirildi.\r\n\r\nOperasyonda M.K. ile beraberindeki E.İ. (24) ve A.K. (53) polis ekiplerince yakalanarak gözaltına alındı.\r\n\r\nEmniyetteki işlemlerinin ardından adliyeye sevk edilen şüphelilerden M.K. ile E.İ. çıkarıldıkları Sulh Ceza Hakimliğince tutuklandı, A.K. ise adli kontrol şartıyla serbest bırakıldı.', 'https://foto.haberler.com/haber/2025/11/16/izmir-de-uyusturucu-operasyonunda-2-zanli-19259759_amp.jpg', 1, '2025-11-16 17:35:42', '2025-11-16 20:35:42', 'Admin'),
(18, 'Son Dakika', 'Milli Eğitim Bakanı Yusuf Tekin’in Malatya Programı', 'Bakan Tekin, Malatya’ya ziyarette bulunacak: valiliği, büyükşehir belediyesini ve AK Parti il başkanlığını ziyaret edecek. \r\n\r\n\r\nAyrıca Geleneksel Abdal Musa Lokması etkinliğine katılarak yerel topluluklarla buluşacak.', 'https://www.gundematlas.com/cropImages/1280x/uploads/haberler/2025/11/7489-okullarda-ara-tatil-sona-eriyor-yarin-ders-zilleri-calacak.jpg', 1, '2025-11-16 17:42:41', '2025-11-16 20:42:41', 'Admin'),
(19, 'Dünya', 'Türkiye’de Gündem Yoğun: Siyasi Trafik, Hava Durumu ve Ekonomide Yeni Beklentiler', 'Türkiye’nin gündemi bugün hem siyasi hem ekonomik açıdan hareketli. TBMM’de güvenlik gündemi yeniden ele alınırken, ilgili bakanlıkların sunumlarının güncellenmesi bekleniyor. Muhalefet cephesinde ise düzenlenen bölgesel mitingler dikkat çekiyor.\r\n\r\nEkonomide gözler, hafta içinde açıklanacak enflasyon beklentileri ve Merkez Bankası’nın olası açıklamalarına çevrilmiş durumda. Uzmanlar, kur ve fiyat istikrarı konusunda yıl sonuna yaklaşılırken piyasaların temkinli seyrettiğini belirtiyor.\r\n\r\nMeteoroloji Genel Müdürlüğü, Karadeniz ve Doğu Anadolu bölgelerinde yağışlı hava uyarısında bulundu. İç kesimlerde sıcaklıklar artarken, bazı yüksek bölgelerde karla karışık yağmur görülebileceği bildirildi.\r\nGünlük yaşamı etkileyen gelişmelerin yanı sıra, kültür-sanat ve spor alanlarında da hafta boyunca çeşitli etkinlikler ve karşılaşmalar planlanıyor.', 'https://finanstaksi.com/cropImages/1280x/uploads/haberler/2025/10/9216-tbmmde-guvenlik-ve-ekonomi-gundemi-yogun-hafta-basliyor.jpg', 1, '2025-11-16 17:43:39', '2025-11-16 20:43:39', 'Admin'),
(20, 'Son Dakika', 'Şehit askerler için Mürted Hava Üssü\'nde hüzünlü cenaze töreni düzenlendi', 'Gürcistan-Azerbaycan sınırında düşen C130 tipi askeri kargo uçağında hayatını kaybeden askerler için Ankara\'da Mürted Hava Üssü\'nde hüzünlü bir askeri tören düzenlendi. 20 askerin cenazesi, tören sonrası toprağa verileceği yerlerine gönderildi.', 'https://www.gundematlas.com/cropImages/1280x/uploads/haberler/2025/11/6743-sehit-askerler-icin-murted-hava-ussunde-huzunlu-cenaze-toreni-duzenlendi.jpg', NULL, '2025-11-16 17:46:02', '2025-11-16 20:46:02', 'Admin'),
(22, 'Ekonomi', 'İŞKUR Gençlik Programı\'na yoğun ilgi: 483 bin başvuru yapıldı', 'İŞKUR Gençlik Programı\'na bugüne kadar 483 bin 331 başvuru yapıldı\r\nÜniversite öğrencilerine kısmi zamanlı çalışma imkânı sunan İŞKUR Gençlik Programı’na bugüne kadar 483 bin 331 başvuru gerçekleştirildi.\r\n\r\nANKARA (AA) - Türkiye İş Kurumunun (İŞKUR), gençlerin iş gücü piyasasına daha donanımlı şekilde katılmalarını sağlamak amacıyla hayata geçirdiği \"İŞKUR Gençlik Programı\"na bugüne kadar 483 bin 331 başvuru yapıldı.\r\n\r\nÇalışma ve Sosyal Güvenlik Bakanlığından yapılan açıklamada, programın Cumhurbaşkanı Recep Tayyip Erdoğan tarafından şubat ayında açıklanarak başlatıldığı, üniversitede okuyan öğrencilerin talebi üzerine kontenjanın 150 bine yükseltildiği hatırlatıldı.\r\n\r\nAçıklamada, programa bugüne kadar 483 bin 331 gencin başvuruda bulunduğu belirtilerek programın, üniversite öğrencilerine kampüs veya üniversite birimlerinde kısmi zamanlı çalışma imkânı sunarak hem iş tecrübesi hem de gelir desteği sağladığı ifade edildi.\r\n\r\nDevlet üniversiteleriyle işbirliği içinde yürütülen programın, gençlerin iş gücü piyasasına daha donanımlı şekilde katılmalarını hedeflediği kaydedildi. Programa ilişkin ayrıntıların \"genclik.iskur.gov.tr\" internet adresinden erişilebileceği bildirildi.\r\n\r\nÇalışma ve Sosyal Güvenlik Bakanı Vedat Işıkhan, programa ilişkin sosyal medya hesabından yaptığı paylaşımda şu ifadeleri kullandı:\r\n\r\n\"Gençlerimizden İŞKUR Gençlik\'e yoğun ilgi. Gençliğin lideri Sayın Cumhurbaşkanımız, üniversite okuyan evlatlarımızın isteğiyle kontenjanımızı 150 bine yükseltmişti. Bugün itibarıyla 483 bin 331 başvuru aldık. Gençlerimize teşekkür ediyoruz. Türkiye Yüzyılı\'nın sahibi gençlerimiz için çalışmaya devam ediyoruz.\"', 'https://www.gundematlas.com/cropImages/1280x/uploads/haberler/2025/11/8017-iskur-genclik-programina-yogun-ilgi-483-bin-basvuru-yapildi.jpg', 6, '2025-11-16 17:48:57', '2025-11-16 20:48:57', 'admin'),
(24, 'Ekonomi', 'Kasalar dolusu balığı bedava dağıttılar! Nedeni de bir o kadar ilginç', 'Paşalimanı Adası\'nda balıkçılık yapan vatandaşlar, kasalar dolusu balığı \"Biz balık tutunca 15 kiloluk kasayı 300-500 liraya alıyorlar, ama kilosunu 200 liraya millete satıyorlar\" diyerek köylüye bedava dağıttı.\r\nPaşalimanı Adası\'nda balıkçılar, alım fiyatlarının düşüklüğüne tepki olarak kasalar dolusu balığı halka ücretsiz dağıttı.\r\nBalıkçılar, 15 kiloluk kasa balığın 300-500 liraya alındığını, ancak kilosunun 200 liraya satıldığını belirtti.\r\nBalıkçıların tepkisi, aracılar ve toptancıların uyguladığı fiyat politikalarına yönelikti.\r\nBalıkçılıkla geçimini sağlayan vatandaşlar, Paşalimanı Adası\'nda dikkat çeken bir hamle yaptı. Balıkçılar, Teknelerinden çıkan kasalar dolusu balığı, alım fiyatlarının düşüklüğüne tepki göstererek halka ücretsiz dağıttılar.\r\n\r\nNEDENİ BİR HAYLİ İLGİNÇ\r\nBalıkçıların isyanı, aracıların ve toptancıların uyguladığı fiyat politikalarına yönelik oldu. Balıkçılar, tuttukları balıkların toptan satış fiyatıyla pazardaki nihai tüketici fiyatı arasındaki devasa farka şu sözlerle tepki gösterdi:\r\n\r\n\"Balıklar şu an satılmıyor, talep yok. Biz de halka bedava veriyoruz. Biz balık tutunca 15 kiloluk kasayı 300-500 liraya alıyorlar, ama kilosunu 200 liraya millete satıyorlar.\"', 'https://foto.haberler.com/haber/2025/11/16/kasalar-dolusu-baligi-bedava-dagittilar-nedeni-19259557_1259_amp.jpg', 1, '2025-11-16 17:56:53', '2025-11-16 20:56:53', 'Admin'),
(25, 'Dünya', 'COP30\'DA STIELL\'DEN GELİŞMİŞ ÜLKELERE ÇAĞRI: “FİNANSMAN TAAHHÜTLERİNİ YERİNE GETİRİN”', 'BELEM (AA) - Birleşmiş Milletler (BM) İklim Değişikliği Çerçeve Sözleşmesi (UNFCCC) İcra Sekreteri Simon Stiell, \"İklim finansmanı, iklim eyleminin can damarıdır\" diyerek gelişmiş ülkeleri taahhütlerini yerine getirmeye çağırdı.\r\n\r\nBrezilya\'nın Belem kentinde devam eden BM İklim Değişikliği Çerçeve Sözleşmesi 30. Taraflar Konferansı (COP30) Üçüncü Üst Düzey Bakanlar Diyaloğu kapsamında konuşan Stiell, iklim finansmanına yönelik planların uygulamaya dönüşmesinin kritik önem taşıdığını vurguladı.\r\n\r\nToplantının amacının, gelişmekte olan ülkelerin hangi kaynaklara güvenebileceğine dair netlik sağlayarak güven inşa etmek olduğunu belirten Stiell, “Paris Anlaşması’ndan bu yana küresel iklim finansmanı akışları arttı. Yeni ortaklıklar kuruldu, temiz enerji ve dayanıklılık yatırımları büyüyor. Ancak gelişmiş ülkelerin finansman sözlerini yerine getirmesi şart.” dedi.', 'https://www.gundematlas.com/cropImages/1280x/uploads/haberler/2025/11/6849-bmden-gelismis-ulkelere-cagri-iklim-finansmani-taahhutlerini-yerine-getirin.jpg', 1, '2025-11-16 18:00:02', '2025-11-16 21:00:02', 'Admin'),
(26, 'Dünya', 'Rusya: ABD ile Alaska\'da sağlanan uzlaşılar, Ukrayna krizinin çözümü için iyi bir yol', 'Kremlin Dış Politika Danışmanı Yuriy Uşakov, ABD ile Ukrayna krizinin çözümüyle ilgili temasların Alaska zirvesinde sağlanan uzlaşılar temelinde sürdüğünü belirterek \"Bu uzlaşıların barışçıl çözüme ulaşmak için iyi bir yol olduğunu düşünüyoruz.\" dedi.\r\n\r\nUşakov, Rossiya-1 televizyon kanalına Rusya ile ABD arasındaki ilişkilere dair açıklamalarda bulundu.\r\n\r\nRusya Devlet Başkanı Vladimir Putin ile ABD Başkanı Donald Trump arasında yeniden zirve yapılması ihtimalini değerlendiren Uşakov, \"Görüşme bir süreliğine ertelendi ancak bu konuda temaslar sürüyor. Washington ile Moskova arasında liderlerin herhangi bir yerde görüşmesi konusunda mutabık kalınırsa, teknik ve siyasi sorunlar ikinci planda kalacak.\" şeklinde konuştu.\r\n\r\nPutin ile Trump\'ın ağustosta Alaska\'nın Anchorage kentinde zirve düzenlediklerini anımsatan Uşakov, \"ABD ile Ukrayna krizinin çözümüyle ilgili temaslar, Anchorage zirvesinde sağlanan uzlaşılar temelinde sürüyor. Bu uzlaşıların barışçıl çözüme ulaşmak için iyi bir yol olduğunu düşünüyoruz.\" ifadelerini kullandı.\r\n\r\nABD tarafından Alaska\'daki anlaşmalardan vazgeçildiğine dair resmi kanallar üzerinden sinyal almadıklarını dile getiren Uşakov, şöyle devam etti:\r\n\r\n\"Çok sayıda açıklama yapıldı. Elbette bu açıklamalar durumun gidişatına, Avrupalılar ve Ukraynalılar ile yapılan görüşmelere göre yapılıyor. Çok sinyal var. Bazıları hoşumuza gidiyor, bazıları hoşumuza gitmiyor. Ancak her şeyin temeli Anchorage zirvesi. Burada sağlanan uzlaşılar Kiev\'e iletildi. Ancak bunlar Kiev ve birçok Avrupa ülkesinin hoşuna gitmiyor. Yani bu uzlaşılar, barışçıl çözümün yerine savaşın devam etmesini isteyenlerin hoşuna gitmiyor.\"', 'https://im.haberturk.com/l/2025/11/16/ver1763289779/3838208/jpg/960x540', 1, '2025-11-16 18:03:07', '2025-11-16 21:03:07', 'Admin'),
(28, 'Magazin', 'Teoman: Rock yıldızı olarak yaşamıyorum', 'Ünlü şarkıcı Teoman, iş dünyası, markalar ve yaratıcı endüstrileri bir araya getiren \'Brand Week İstanbul\'un konuğu oldu. Haliç Kongre Merkezi\'nde düzenlenen program kapsamında şarkıcı, Yekta Kopan\'ın moderatörlüğünde \'Bir Rockstar\'ın Varoluşla İmtihanı\' başlıklı söyleşide konuştu.\r\n\r\nTeoman, genç yaşlarında \"Ben kimim?\", \"Hayattaki yerim nedir?\" sorularıyla varoluşçuluktan çok etkilendiğini ve bu alandaki kitapları okuduğunu söyledi. Birçok düşünürün kendi kişiliğini ve şarkı yazarlığını derinden etkilediğini belirten Teoman, \"İnsan o yıllarda çok etkilenince bütün kişiliğini de o temel üzerine kuruyor. Albert Camus, Jean-Paul Sartre ve Milan Kundera derken benim şarkı yazarlığım da onlardan başlayan, onlara benzer bir yer halini aldı.\" dedi.', 'https://im.haberturk.com/2025/11/15/3838002_cbe888f2c8540609349561c3ac90fd7b.jpg', 1, '2025-11-16 18:12:05', '2025-11-16 21:12:05', 'Admin'),
(29, 'Magazin', 'Çağrı Şensoy: Bir hayal için ter döküyoruz', 'Birlikte bir projede yer alıp almayacakları sorusu üzerine konuşan Çağrı Şensoy; \"Uzunca bir süredir bir heyecanın içindeyiz, bir hayal için ter döküyoruz. Yakında sonucunu alacağız diye umuyorum\" yanıtını verdi. Burak Özçivit ise detay vermekten kaçınarak; \"Zamanı gelince açıklayacağız\" demekle yetindi.', 'https://im.haberturk.com/2025/11/14/3837777_7736031a46fbdb9eea72bb3225fbb6fe.jpg', 1, '2025-11-16 18:12:50', '2025-11-16 21:12:50', 'Admin'),
(30, 'Teknoloji', 'Rusya\'da yapay zekalı insansı robotu tanıtım sırasında yere düştü', 'HENÜZ TEST AŞAMASINDAYDI!\r\nRus robotik firması Idol\'ün CEO\'su Vladimir Vitukhin, robotun henüz test aşamasında olduğunu ifade etti. Vitukhin, \"Umarım bu hata bir tecrübeye dönüşür\" dedi.\r\n\r\nGeliştiricilere göre robot, iki ayak üzerinde yürümek, nesneleri hareket ettirmek ve insanlarla iletişim kurmak olmak üzere 3 temel işlevi yerine getirebiliyor, 6 saate kadar otonom olarak çalışabiliyor. Geliştiriciler, böyle bir robotun imalat ve lojistik sektörlerinin yanı sıra bankalarda, havaalanlarında veya diğer kamusal alanlarda da kullanılabileceğine inanıyor. Robotun gerçekçi yüz ifadeleri ve duygusal tepkilerinin \"Aidol\'ü diğer küresel modellerden ayıran temel özellik\" olduğu, \"yüzünün\" en az 12 temel duyguyu gösterebildiği belirtiliyor.', 'https://im.haberturk.com/l/2025/11/13/ver1763026849/3837538/jpg/960x540', 1, '2025-11-16 18:13:48', '2025-11-16 21:13:48', 'Admin'),
(31, 'Teknoloji', 'GençBizzTech 2026 başlıyor', 'Hayata geçirilen GençBizzTech 2026 Bilim ve Teknoloji Temelli Lise Girişimcilik Programı, Türkiye genelindeki devlet fen liselerinde okuyan gençleri inovasyon dolu bir serüvene davet ediyor.\r\n\r\nGençlerin bilim, teknoloji, yapay zekâ ve sürdürülebilirlik alanlarında fark yaratmalarını amaçlayan kapsamlı girişimcilik programı GençBizzTech ile öğrenciler teknik bilgi kazanımının yanı sıra, yaratıcılık, takım ruhu, liderlik ve problem çözme becerilerini de geliştirebilecek. Program ile öğrenciler, öğrendikleriyle yenilikçi projeler geliştirerek deneyim edinme fırsatı yakalayacak.\r\n\r\nGEN-E 2026\'YA HAK KAZANACAK\r\nProgramın sonunda Türkiye finaline katılmaya hak kazanacak 10 ekip, iş dünyasından mentorlar ile bire bir çalışma fırsatı elde edecek. Mayıs ayında gerçekleşecek büyük finalde finalist ekipler projelerini jüriye sunarken, birinci olan ekip Letonya’da düzenlenecek Avrupa’nın en büyük girişimcilik festivali GEN-E 2026’ya katılma hakkı elde edecek. Ayrıca genç girişimciler, teknoloji ve inovasyon odaklı özel ödüller için yarışma ve GEN-E 2026 sanal fuarında stant açarak uluslararası tanıtım yapma şansına sahip olacak.\r\n\r\nGenç Başarı Eğitim Vakfı Yönetim Kurulu Başkanı Evrim Bayam, programla ilgili yaptığı açıklamada şunları söyledi:\r\n\r\n“GençBizzTech, yalnızca bir girişimcilik programı değil; gençlerin hayal gücünü bilimle buluşturup fikirlerini gerçeğe dönüştürdükleri bir platform. Amacımız, Türkiye’nin dört bir yanındaki fen liseli öğrencilerin potansiyellerini erken yaşta keşfetmelerine, teknoloji odaklı çözümler geliştirerek toplumsal etki yaratmalarına destek olmak. Her yıl artan katılım ve başarı hikâyeleri, gençlerin geleceği dönüştürme isteğinin en büyük göstergesi.”\r\n\r\nProgramın başvuruları 21 Aralık 2025’e kadar sürecek.', 'https://im.haberturk.com/l/2025/11/13/ver1763038384/3837612/jpg/960x540', 1, '2025-11-16 18:14:26', '2025-11-16 21:14:26', 'Admin'),
(32, 'Spor', 'Arda Güler\'e dev talip: Real Madrid\'den flaş karar!', 'Real Madrid\'de forma giyen milli futbolcu Arda Güler için İngiliz devi Manchester City\'den çılgın bir teklif geldi.\r\n\r\nFichajes\'te yer alan habere göre; İngiliz ekibinin, genç yıldızı kadrosuna katmak adına yaklaşık 100 milyon euroyu gözden çıkardığı öne sürüldü.', 'https://im.haberturk.com/2025/11/16/3838237_e6657d16a6f490da44a60e6bff92f1a9_640x640.jpg', 1, '2025-11-16 18:16:40', '2025-11-16 21:16:40', 'Admin'),
(33, 'Spor', 'Karim Adeyemi\'ye dev para cezası!', 'Borussia Dortmund\'un başarılı futbolcularından Karim Adeyemi, muşta ve elektroşok silahı taşıdığı gerekçesiyle 450 bin avro para cezası aldı.\r\n\r\nAlman Bild gazetesinin haberine göre 23 yaşındaki futbolcu, muşta ve elektroşok silahı bulundurduğu için 7 bin 500 avroluk 60 taksit halinde ödenmek üzere 450 bin avro para cezasına çarptırıldı.\r\n\r\nHaberde, şu anda Almanya Milli Takımı kampında bulunan Adeyemi\'ye herhangi bir yaptırım uygulanmayacağı ifade edildi.', 'https://im.haberturk.com/l/2025/11/16/ver1763310463/3838262/jpg/960x540', 1, '2025-11-16 18:17:05', '2025-11-16 21:17:05', 'Admin'),
(34, 'Son Dakika', 'PKK’nın Zap\'ı boşaltması ne anlatıyor?', 'Öztürk, bölgedeki durumu şu şekilde aktardı:\r\n\r\nPKK, Zap ve Metina bölgesinde hareket kabiliyetini kaybetmişti.\r\n\r\nTürk Silahlı Kuvvetleri, operasyonlarla bölgeyi etkisiz hale getirdi ve kilit noktaları kapattı.\r\n\r\nBölgede bulunan mağaralar ve tünellerin büyük kısmı imha edildi, tek tük saklanan teröristler de bölgeden kaçtı.\r\n\r\nKaçan teröristlerin Kandil bölgesine doğru ilerlediği tahmin ediliyor ve hareketleri İHA ve SİHA’larla sürekli takip ediliyor.', 'https://image.cnnturk.com/i/cnnturk/75/770x0/691adf61d01eadb4b060e8d9.jpg', 20, '2025-11-17 09:03:53', '2025-11-17 12:03:53', 'Admin'),
(35, 'Son Dakika', 'SON DAKİKA DEPREM! 17 Kasım Pazartesi deprem mi oldu, nerede, kaç şiddetinde? Son dakika deprem mi oldu? AFAD ve Kandilli Rasathanesi son depremler listesi!', 'arsıntı hisseden çok sayıda vatandaş \"17 Kasım Pazartesi deprem mi oldu? Nerede, kaç şiddetinde? Ege Denizi\'nde deprem mi oldu? Sındırgı\'da deprem mi oldu?, Simav\'da deprem mi oldu?, Osmaniye\'de deprem mi oldu?\" sorularına yanıt arıyor. İşte, 17 Kasım Pazartesi günü Kandilli Rasathanesi ve AFAD tarafından yayımlanan son depremler listesi...\r\n\r\nBUGÜN DEPREM Mİ OLDU? 17 KASIM PAZARTESİ\r\n\r\nKandilli Rasathanesi\'nden yapılan son açıklamaya göre son olarak Balıkesir\'de deprem meydana geldi. Saat 09:00\'da meydana gelen MANDIRA-SINDIRGI (BALIKESIR) merkezli  deprem 2.1 şiddetinde gerçekleşti.', 'https://image.cnnturk.com/i/cnnturk/75/770x0/691abce1d01eadb4b060e844.jpg', 5, '2025-11-17 09:15:18', '2025-11-17 12:15:18', 'Admin'),
(37, 'Dünya', 'Trump\'tan Epstein dosyasının açıklanması için çağrı: Saklayacak bir şeyimiz yok', 'ABD Başkanı Donald Trump, reşit olmayan kız çocuklarına yönelik fuhuş ağı kurmakla suçlanan ve 2019’da cezaevinde ölü bulunan milyarder Jeffrey Epstein’a ilişkin tüm hükümet belgelerinin açıklanması için Temsilciler Meclisindeki Cumhuriyetçilerin oy kullanması gerektiğini söyledi.\r\n\r\nTrump, ABD merkezli Truth Social hesabından yaptığı açıklamada, \"Saklayacak hiçbir şeyimiz yok ve Demokratların, Cumhuriyetçi Partinin büyük başarısından, özellikle de \'kapanma\' konusunda elde ettiğimiz son zaferden sonra Demokratların dikkatleri başka yöne çekmek için radikal solcu deliler tarafından uydurulan bu yalandan artık vazgeçme zamanı geldi.\" ifadesini kullandı.', 'https://image.cnnturk.com/i/cnnturk/75/740x416/691ac2d2586197a571ccfe5b.jpg', 1, '2025-11-17 11:10:23', '2025-11-17 14:10:23', 'Admin'),
(40, 'Teknoloji', 'Aselsan\'dan dünyada bir ilk! Bayraktar KIZILELMA’ya ASELSAN TOYGUN entegre edildi', 'TOYGUN TEKNOLOJİSİNİN ÖNEMİ\r\nDüşük görünürlüklü elektro-optik hedefleme sistemleri, savaş uçaklarının düşman radarları tarafından tespit edilmesini zorlaştırıyor. Bu sistemler, hassas hedefleme ve yüksek doğrulukla görev yaparak savaş alanında kritik avantajlar sağlıyor.\r\n\r\nTOYGUN’un KIZILELMA’ya entegrasyonu, insansız savaş uçaklarının operasyonel kabiliyetlerini 5. nesil seviyesine taşıdı.\r\n\r\nBayraktar KIZILELMA, TOYGUN ile birlikte artık sadece bir insansız savaş uçağı değil, aynı zamanda düşük görünürlük ve yüksek hassasiyetle görev yapabilen bir “gelecek nesil hava gücü” haline geldi.', 'uploads/1763380158_kızıl.jpeg', 1, '2025-11-17 11:49:18', '2025-11-17 14:49:18', 'Admin'),
(41, 'Teknoloji', 'Genel Müdür Dr. Ali Taha Koç: “2025 hedefleri yukarı yönlü güncellendi”', 'Turkcell, 2025 yılının 3’üncü çeyreğinde de güçlü ve sürdürülebilir büyümesini devam ettirdiğini açıkladı. Bu çeyrekte elde edilen 569 bin net faturalı mobil abone artışının katkısıyla toplamda 39 milyon abone bazına ulaşan şirketin Genel Müdürü Dr. Ali Taha Koç, sonuçlara ilişkin olarak şu değerlendirmelerde bulundu:\r\n\r\n“Yılın 3’üncü çeyreğinde, güçlü finansal ve operasyonel sonuçlara imza attık. Bu performansı, ülkemizin dijital geleceği için bir dönüm noktası olan 5G yetkilendirme ihalesindeki başarımızla taçlandırmaktan büyük gurur duyuyoruz. 16 Ekim’de gerçekleşen ihalede, bir operatörün alabileceği en yüksek kapasite olan toplam 160 MHz frekans hakkını elde ederek liderliğimizi bir kez daha tescilledik. 30 yılı aşan deneyimimiz, Türkiye\'nin dört bir yanını kapsayan geniş baz istasyonu ağımız ve hızla artan baz istasyonu fiberleşme oranımızla, 5G’ye hazır olmakla kalmayıp; bu dönüşüme liderlik etmeye de kararlıyız.”\r\n\r\nMOBİL ABONE SAYISI 39 MİLYONU AŞTI\r\nMüşteri bazlarını genişletmeye devam ettiklerini vurgulayan Koç, “3’üncü çeyrekte elde edilen 569 bin net faturalı mobil abone kazanımıyla, son 12 aydaki net faturalı abone artışımız 2 milyonu, toplam mobil abone bazımız ise 39 milyonu aştı. Gelire olan yüksek katkısı nedeniyle stratejik odağımızda yer alan faturalı abone oranımız ise yıllık bazda 4,6 puan artışla %79’a yükseldi. Diğer taraftan, abone bazımızı yapay zekâ destekli mikro-segment yaklaşımıyla yöneterek, ihtiyaç odaklı teklifler sunmaya devam ettik. Bununla birlikte, faturalı abone oranındaki artışın da etkisiyle Mobil Bileşik ARPU’da (M2M hariç) yıllık %11,9 büyüme elde ettik” ifadelerini kullandı.\r\nVERİ MERKEZİ VE BULUT GELİRLERİNDE %51 BÜYÜME\r\nYapılan açıklamada veri merkezi ve bulut işimize bugüne kadar toplam 545 milyon Euro yatırım yapıldığı da vurgulanırken, yeni nesil veri merkezlerinin aktif kapasitesinin, bu çeyrekte 8,4 MW artarak 50 MW’a ulaştığı belirtildi. Toplam veri merkezi ve bulut gelirlerimiz ise önceki yılın aynı dönemine göre %51 büyüme kaydedildiği açıklandı.\r\n\r\nGOOGLE CLOUD İLE HİPER ÖLÇEKLİ BULUT BÖLGESİ\r\nVeri merkezi ve bulut bilişim alanında San Francisco’da çok önemli bir iş birliğine imza attıklarını vurgulayan Ali Taha Koç, “Google Cloud ile yaptığımız anlaşmayla Türkiye’nin ilk hiper ölçekli bulut bölgesini kuracağız. İş birliğimiz Turkcell’in Türkiye’nin dijital dönüşümündeki lider rolünü bir kez daha ortaya koydu. Anlaşma kapsamında kuracağımız veri merkezleri ve bulut teknolojilerine 1 milyar dolarlık yatırım yapacağız. Google Cloud ile güçlerimizi birleştirerek ülkemizde yapay zekâ odaklı inovasyonların önünü açacağız” dedi.\r\n\r\n4 İLDEKİ GÜNEŞ ENERJİSİ SANTRALLERİNİN TOPLAM KAPASİTESİ 37,5 MW’YE ULAŞTI\r\nKoç verimlilik hedeflerine de vurgu yaparak, \"Gerçekleştirdiğimiz yatırımlar sayesinde, Uşak, Van, Balıkesir ve Yozgat’ta faaliyet gösteren güneş enerjisi santrallerimizin toplam kapasitesi, üçüncü çeyrek itibarıyla 37,5 MW seviyesine ulaştı. Stratejik öneme sahip bu adımları, Türkiye’nin sürdürülebilir yarınlarına yönelik yatırımlar olarak görüyoruz\" şeklinde konuştu.\r\n\r\nGEÇEN YILIN AYNI DÖNEMİNE GÖRE YÜZDE 11,2 ARTIŞLA 59,5 MİLYAR TL KONSOLİDE GELİR\r\nŞirketten yapılan üçüncü çeyrek finansal rapor açıklamasına göre, ana iş kolundaki performansın yanı sıra techfin ve veri merkezi gibi stratejik odak alanlarında konsolide gelirlerin geçen yılın aynı dönemine göre yüzde 11,2 artarak 59,5 milyar TL seviyesine ulaştığı vurgulandı. FAVÖK\'ün yüzde 10,5 büyüyerek 26,2 milyar TL’ye yükseldiği kaydedilirken, yüzde 43,9 FAVÖK marjı ile operasyonel kârlılığın da yılın üçüncü çeyreğinde korunduğu vuruglandı. Net kârın ise 5,4 milyar TL seviyesinde gerçekleştiği belirtildi.', 'uploads/1763380236_5g.jpeg', 1, '2025-11-17 11:50:36', '2025-11-17 14:50:36', 'Admin'),
(42, 'Ekonomi', 'Doğu Karadeniz\'in bal ihracatı 10 ayda 1,8 milyon doları aştı', 'Doğu Karadeniz İhracatçılar Birliği Yönetim Kurulu Başkan Yardımcısı Ahmet Hamdi Gürdoğan, bölgeden yılın 10 ayında 11 ülkeye 414 ton bal ihraç edildiğini söyledi.\r\n\r\nİhracattan 1 milyon 834 bin 520 dolar kazanç sağlandığını belirten Gürdoğan, Almanya, ABD ve Birleşik Arap Emirlikleri\'nin en fazla dış satım yapılan ülkeler olduğunu açıkladı.\r\n\r\nGürdoğan, Almanya\'nın en önemli ihraç pazarlarından olduğuna işaret ederek, \"Bölgemizden Almanya\'ya bu dönem 765 bin 195 dolarlık bal ihraç edildi. Geçen yılın aynı döneminde ise bu ülkeye 376 bin 367 dolarlık ihracat gerçekleştirilmişti. Böylece Almanya\'ya yılın 10 ayında yapılan bal ihracatı, değerde yüzde 103 arttı.\" dedi.\r\n\r\n\"Ürdün, Fas ve Hollanda gibi yeni pazarlara açılarak ihracat ağımızı genişlettik\"\r\nAlmanya\'yı 604 bin 82 dolarla ABD, 190 bin 811 dolarla Birleşik Arap Emirlikleri\'nin takip ettiğini belirten Gürdoğan, geçen yılın aynı döneminden farklı şekilde Ürdün, Fas ve Hollanda\'ya da bu dönem bal satıldığını kaydetti.\r\n\r\nGürdoğan, bal ihracatını önemsediklerine dikkati çekerek, şunları dile getirdi:\r\n\r\n\"Hedefimiz, bal ihracatımızı daha fazla ülkeye ulaştırmak ve bölge ekonomisine katkıyı artırmak. Bu dönemde Ürdün, Fas ve Hollanda gibi yeni pazarlara açılarak ihracat ağımızı genişlettik. İlerleyen günlerde ihracat potansiyelimiz ve ülke çeşitliliğimizin giderek daha da artacağına inanıyoruz.\"', 'https://trthaberstatic.cdn.wp.trt.com.tr/resimler/1586000/bal-depophotos-1587930.jpg', 1, '2025-11-17 11:54:16', '2025-11-17 14:54:16', 'admin'),
(43, 'Spor', 'Galatasaray çekincesi Inter\'e karar aldırdı! Hakan Çalhanoğlu...', 'AMilli Takım\'ın ve Inter\'in başarılı futbolcusu Hakan Çalhanoğlu için İtalyan basınından dikkat çeken bir iddia gündeme geldi.\r\n\r\nSezon öncesi yaz transfer döneminde İtalyan devinden ayrılması beklenirken takımda kalan milli futbolcu için yeni sözleşme görüşmeleri yakın zamanda başlayabilir.\r\n\r\nGalatasaray\'ın da yakından ilgilendiği Hakan Çalhanoğlu\'nun sözleşmesi 2027 yazında sona eriyor.\r\n\r\n\r\n\r\nINTER YENİ KONTRAT İMZALAMAK İSTİYOR\r\n\r\nCalciomercato\'da yer alan haberde, Inter yönetiminin Sarı-kırmızılıların Hakan\'a ilgisinin farkında olduğu ve tecrübeli futbolcunun başarılı performansı nedeniyle yeni sözleşme imzalamak istediği belirtildi.\r\n\r\nHaberde, Hakan Çalhanoğlu\'nun maaşının 6.5 milyon Euro olduğu ve Lautaro Martinez\'den sonra kulübün en çok kazanan ikinci oyuncusu olduğuna dikkat çekildi.\r\n\r\nYine ek olarak Galatasaray\'ın devre arası transfer döneminde İtalya\'da oyuncu ile yeniden bir görüşme planladığı da aktarıldı.\r\n\r\nBu sezon Inter formasıyla 13 resmi maçta 6 gol ve 3 asist yapan Çalhanoğlu, son olarak Milli Takım\'ın Bulgaristan\'la oynadığı karşılaşmada ağları havalandırmıştı.', 'https://i12.haber7.net//haber/haber7/photos/2025/47/eAkO1_1763378541_6162.jpg', 5, '2025-11-17 11:55:58', '2025-11-17 14:55:58', 'admin'),
(44, 'Son Dakika', 'Kırşehir\'de Tefecilik Operasyonunda 4 Tutuklama', 'Kırşehir\'de Tefecilik ve Dolandırıcılık Operasyonu\r\nKırşehir\'de yapılan bir operasyonda, tefecilik ve nitelikli dolandırıcılık iddialarıyla 7 şüpheli gözaltına alındı.\r\n\r\nOperasyon ve Gözaltılar\r\nKırşehir İl Emniyet Müdürlüğü Kaçakçılık ve Organize Suçlarla Mücadele Şubesi, Kırşehir Cumhuriyet Başsavcılığı koordinasyonunda, tefecilik faaliyeti yürüten zanlılara yönelik bir çalışma başlattı. Bu kapsamda, belirlenen 9 adrese aynı anda operasyon düzenlendi.\r\n\r\nEle Geçirilenler\r\nOperasyon sonucunda M.D, A.H, B.D, H.G, M.D, K.I ve Ş.A adlı şahıslar yakalandı. İkametgahlar ve iş yerlerinde gerçekleştirilen aramalarda cep telefonları, çok sayıda çek-senet ve çeşitli delil niteliğindeki materyaller ele geçirildi.\r\n\r\nAdli Süreç\r\nGözaltına alınan şüpheliler, emniyetteki işlemlerinin ardından adliyeye sevk edildi. Nöbetçi hakimlik tarafından yapılan değerlendirmeler sonucunda, M.D, A.H, B.D ve H.G tutuklanırken, diğer 3 zanlı adli kontrol şartıyla serbest bırakıldı.', 'https://img.cdn.haber365.com/uploads/images/news/kirsehir039de-duzenlenen-quottefecilikquot-ve-quotnitelikli-dolandiricilikquot-operasyonunda-gozaltina-alinan-7-257-body.jpg', 1, '2025-11-17 17:01:03', '2025-11-17 20:01:03', 'Admin');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kategoriler`
--

CREATE TABLE `kategoriler` (
  `id` int(11) NOT NULL,
  `kategori_adi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kategoriler`
--

INSERT INTO `kategoriler` (`id`, `kategori_adi`) VALUES
(8, 'Dünya'),
(4, 'Ekonomi'),
(7, 'Magazin'),
(3, 'Son Dakika'),
(5, 'Spor'),
(2, 'Teknoloji');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'kullanıcı',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'kerem', 'kerem', 'kerem@gmail.com', '$2y$10$/97KQ/nuOqiguNruU0ETGOyFXXFcaIGQnkU.7tyyEchJFx70.qiAa', 'editor', '2025-11-16 15:31:05'),
(3, 'xaxa', 'rae', 'fdsfxsd@gmail.com', '$2y$10$hBXjkoJXFfDFZfYKxLUtwuzREP3XG22Px6DCqt/EycpfSxbMmSRLG', 'kullanıcı', '2025-11-17 08:48:24'),
(4, 'brrxleiss', 'brxleis', 'brxleis@gmail.com', '$2y$10$a0PVEYpOAoDzqnOYTbyxP.exq/Rp/nyPjCUM.DMx1sXNuStwjMik.', 'kullanici', '2025-11-17 08:50:49'),
(5, 'kerem1', 'kerem1', 'kexrem@gmail.com', '$2y$10$AA6juNDFdMzSfBCvVdi5GuNo5vRYfPm2Of3EuH0k73X/ShLhrEtwO', 'editor', '2025-11-17 08:55:48'),
(8, 'UFUK YÜKSEL', 'ufk', 'ufk@gmail.com', '$2y$10$.gPBQIJdU0Dg2Xp00pSineBw8Bj.zVkTxYwwywMBGTRVDaMWIfFf2', 'kullanıcı', '2025-11-17 09:13:26'),
(10, 'admin', 'admin', 'admin@gmail.com', '$2y$10$xt9qVkYuC18iCxKah0LDUehtRz7FgtTcWEol79BkjanBXofiRHae6', 'admin', '2025-11-17 11:15:58'),
(11, 'aa', 'aa', 'aa@gmail.com', '$2y$10$RlDNjj1RK/vZfcrUPuTwieK1YaRtBn5JBLbd1sIbPaXzICIwttEiy', 'kullanıcı', '2025-11-17 12:23:09'),
(12, 'Kerem Cem', 'kerembey12', 'mldsgdsl@gmail.com', '$2y$10$23EVoLNgsSUYUleoxlSmv.8sknGtBUEnYeNPfWUot.rMCJdd7dDyS', 'user', '2025-11-17 14:04:02'),
(13, 'Ufuk Can', 'ufuk', 'ufukcan@gmail.com', '$2y$10$XOxt4Uyan7uyQSGPg/jVfOdIb6.4VgMak658wRrViCjPBaBJp0Jfu', 'admin', '2025-11-17 16:23:54'),
(14, 'Krm', 'krmby', 'fdsf@gmail.com', '$2y$10$rTbnHgFkKTGXgtHsZ4p9ceT7Kpiqvi1lP2DZ5SYkybwia0Uh19Fjm', 'user', '2025-11-17 16:33:44');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Tablo için indeksler `haberler`
--
ALTER TABLE `haberler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kategoriler`
--
ALTER TABLE `kategoriler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kategori_adi` (`kategori_adi`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `haberler`
--
ALTER TABLE `haberler`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Tablo için AUTO_INCREMENT değeri `kategoriler`
--
ALTER TABLE `kategoriler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
