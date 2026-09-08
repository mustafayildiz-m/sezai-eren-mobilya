# Koyu Vitrin — site tasarımı yenileme

Tarih: 2026-09-08
Durum: onaylandı, uygulanıyor

## Amaç

Siteyi daha şık ve modern göstermek, mobil deneyimi düzeltmek, ana sayfa
görselini yenilemek. SEO yapısına dokunmadan.

## Kapsam dışı

- **Yeni proje eklenmeyecek.** Elde kullanılmamış gerçek fotoğraf kalmadı
  (77'nin 76'sı kullanıldı). Yeni proje ancak Sezai usta yeni fotoğraf
  gönderince eklenir.
- **Stok / yapay üretilmiş görsel kullanılmayacak.** Ana sayfa ve proje
  galerisi yalnızca gerçek işi gösterir. Gerekçe: ana sayfadaki görsel
  "bunu biz yaptık" anlamına gelir; müşteri onu isteyerek gelir.
- **SEO değişmiyor:** URL'ler, başlıklar, meta, JSON-LD, sitemap, ilçe ve
  hizmet landing sayfaları aynen korunur.

## Yön: Koyu Vitrin

Baştan sona koyu zemin, altın vurgu. Fotoğraflar showroom spotu altında gibi
öne çıkar. Bu yön, telefonla çekilmiş ve pozlaması değişken fotoğrafları en
affedici olan seçenek — koyu zemin arka plan dağınıklığını ve renk farklarını
yutuyor.

### Renk paleti (yeni tokenlar)

| Token | Değer | Kullanım |
|---|---|---|
| `bg` | `#141210` | sayfa zemini |
| `surface` | `#1B1815` | bölüm zemini |
| `surface2` | `#232019` | kart |
| `line` | `#2E2A24` | ayırıcı |
| `ink` | `#EFE7DA` | ana metin |
| `gold` | `#C9A227` | vurgu, CTA |
| `gold-light` | `#E3C25E` | hover |

Mevcut tokenlar (`walnut`, `cream`, `copper`, `ground`, `beige`, `sand`)
**korunuyor** — yönetim paneli onları kullanıyor ve panel yeniden
tasarlanmıyor.

## Ana sayfa hero

Bölünmüş yerleşim: solda tipografi ve iki aksiyon, sağda dikey gerçek detay
fotoğrafı (doğal kenar ceviz tabaka + pirinç kulp).

Gerekçe: tek bir kusursuz *oda* fotoğrafına bağımlı olmamak. Yakın plan doku
affedicidir — dağınık arka plan, sarı ampul ışığı ve çarpık açı devreye
girmez. Sezai usta yeni fotoğraf gönderdiğinde tek dosya değişerek güncellenir.

Telefonda fotoğraf üste, metin alta geçer.

## Ana sayfa akışı

1. Hero (bölünmüş)
2. Güven şeridi — 20 yıl / ücretsiz keşif / kendi ekibimiz / tüm Ankara
3. Hizmet kartları (kategoriler, proje sayısıyla)
4. Seçilmiş işler — editoryal ızgara (1 büyük + 2 küçük)
5. Süreç — 4 adım
6. Hizmet bölgesi — 8 ilçe + hizmet linkleri
7. SSS (FAQPage schema'sı zaten var)
8. CTA
9. Footer

## Mobil

- Yapışkan alt aksiyon çubuğu: **Ara / WhatsApp / Teklif Al**
- Sağ altta yüzen yeşil WhatsApp butonu **kaldırılıyor** (içeriği örtüyordu)
- Kategori filtreleri yatay kaydırmalı şerit
- Dokunma hedefleri ≥ 44px
- Hero başlığı taşmadan iki satıra oturur

## Performans ve erişilebilirlik

- Görsellerde `srcset`; sabit en/boy oranlarıyla CLS sıfır
- Hero görseli `preload` + `fetchpriority=high`
- Koyu zeminde metin kontrastı en az WCAG AA
- Klavye odak halkaları görünür
- `prefers-reduced-motion` korunur

## İçerik kararları

- **Uydurma müşteri yorumları kaldırılıyor.** "Ayşe K.", "Murat D." vb.
  gerçek değil ve yanlarında 5 yıldız duruyor. Yerine Süreç ve Garanti
  bölümleri geliyor. Gerçek yorumlar Google İşletme Profili'nde birikince
  eklenir.
- **"850 proje / 600 mutlu müşteri" kaldırılıyor**, "20 yıl" kalıyor.
  İlk ikisi doğrulanamıyor.

## Derleme

Tailwind önceden derleniyor:

```
npx tailwindcss@3 -i src/input.css -o public/assets/app.css --minify
```

`app.css` git'te tutulur; sunucuda Node gerekmez.

## Doğrulama

- 39 PHPUnit testi geçmeli
- Her sayfa 200 dönmeli, PHP uyarısı olmamalı
- Kırık görsel olmamalı
- JSON-LD ayrıştırılabilir kalmalı, sitemap 86 URL
