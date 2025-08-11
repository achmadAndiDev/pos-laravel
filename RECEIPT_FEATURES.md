# Fitur Struk Penjualan

Aplikasi POS ini telah dilengkapi dengan 3 opsi untuk menangani struk penjualan:

## 1. Kirim ke WhatsApp Customer

### Fitur:
- Mengirim struk penjualan dalam format teks yang rapi ke WhatsApp customer
- Otomatis format nomor telepon Indonesia (menambahkan 62 jika dimulai dengan 0)
- Hanya muncul jika customer memiliki nomor telepon

### Cara Kerja:
- Klik tombol "Kirim ke WhatsApp" 
- Sistem akan membuka WhatsApp Web/App dengan pesan struk yang sudah diformat
- Customer dapat langsung menerima struk digital

### Format Pesan WhatsApp:
```
*STRUK PENJUALAN*
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
*Nama Outlet*
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

No: SALE-001
Tanggal: 01/01/2024 10:30
Kasir: Admin
Customer: John Doe

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
*DETAIL PEMBELIAN*
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Produk A
2 x 15.000 = 30.000

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Subtotal: Rp 30.000
*TOTAL: Rp 30.000*

Bayar (Cash): Rp 50.000
Kembalian: Rp 20.000

Terima kasih atas kunjungan Anda! 🙏
```

## 2. Cetak Struk Web Version

### Fitur:
- Membuka halaman struk dalam format yang siap cetak
- Desain responsif untuk berbagai ukuran kertas
- Tombol print dan close tersedia
- Format yang bersih dan profesional

### Cara Kerja:
- Klik tombol "Cetak Struk Web"
- Sistem akan membuka tab baru dengan tampilan struk
- Gunakan tombol "Cetak Struk" atau Ctrl+P untuk mencetak

### Karakteristik:
- Lebar: 300px (cocok untuk struk kecil)
- Font: Courier New (monospace)
- Format: Header, info penjualan, item, total, payment, footer

## 3. Cetak Thermal 58mm (Bluetooth)

### Fitur:
- Khusus untuk integrasi dengan aplikasi Android
- Format data JSON untuk printer thermal 58mm
- Fallback preview untuk testing di web browser

### Cara Kerja di Android:
1. Klik tombol "Cetak Thermal 58mm"
2. Sistem akan memanggil interface Android: `Android.printThermalReceipt()`
3. Data struk dikirim dalam format JSON ke aplikasi Android
4. Aplikasi Android menangani koneksi Bluetooth dan printing

### Cara Kerja di Web Browser (Testing):
1. Klik tombol "Cetak Thermal 58mm"
2. Sistem akan membuka preview window dengan format thermal
3. Data juga tersedia di console untuk debugging

### Format Data JSON:
```json
{
  "outlet_name": "Nama Outlet",
  "sale_code": "SALE-001",
  "sale_date": "01/01/2024 10:30",
  "cashier": "Admin",
  "customer": "John Doe",
  "items": [
    {
      "name": "Produk A",
      "quantity": 2,
      "unit_price": 15000,
      "total_price": 30000
    }
  ],
  "subtotal": 30000,
  "tax_amount": 0,
  "discount_amount": 0,
  "total_amount": 30000,
  "paid_amount": 50000,
  "change_amount": 20000,
  "payment_method": "Cash"
}
```

## Integrasi dengan Android

### Setup di Android Studio:

1. **Tambahkan WebView Interface:**
```java
public class WebAppInterface {
    Context mContext;

    WebAppInterface(Context c) {
        mContext = c;
    }

    @JavascriptInterface
    public void printThermalReceipt(String receiptData) {
        // Parse JSON data
        try {
            JSONObject data = new JSONObject(receiptData);
            
            // Connect to Bluetooth printer
            // Format data untuk printer thermal 58mm
            // Send to printer
            
            Toast.makeText(mContext, "Printing receipt...", Toast.LENGTH_SHORT).show();
        } catch (JSONException e) {
            e.printStackTrace();
        }
    }
}
```

2. **Setup WebView:**
```java
WebView webView = findViewById(R.id.webview);
webView.addJavascriptInterface(new WebAppInterface(this), "Android");
webView.getSettings().setJavaScriptEnabled(true);
```

3. **Implementasi Bluetooth Printing:**
- Gunakan library seperti `BluetoothAdapter` untuk koneksi
- Format data sesuai dengan command printer thermal (ESC/POS)
- Handle error dan status printing

## Routes yang Ditambahkan

```php
// Receipt printing options
Route::get('sales/{sale}/print-receipt', [SaleController::class, 'printReceipt'])->name('sales.print-receipt');
Route::get('sales/{sale}/thermal-receipt', [SaleController::class, 'thermalReceipt'])->name('sales.thermal-receipt');
Route::post('sales/{sale}/send-whatsapp', [SaleController::class, 'sendToWhatsApp'])->name('sales.send-whatsapp');
```

## Controller Methods yang Ditambahkan

1. `printReceipt(Sale $sale)` - Untuk web printing
2. `thermalReceipt(Sale $sale)` - Untuk thermal printing (JSON response)
3. `sendToWhatsApp(Sale $sale)` - Untuk WhatsApp integration

## File yang Dibuat/Dimodifikasi

1. **Controller:** `app/Http/Controllers/Admin/SaleController.php`
2. **View:** `resources/views/admin/sales/show.blade.php`
3. **New View:** `resources/views/admin/sales/receipt-print.blade.php`
4. **Routes:** `routes/web.php`

## Catatan Penting

- Semua fitur hanya tersedia untuk penjualan dengan status "completed"
- WhatsApp hanya muncul jika customer memiliki nomor telepon
- Thermal printing memerlukan integrasi dengan aplikasi Android
- Format nomor telepon otomatis disesuaikan untuk WhatsApp Indonesia

## Testing

1. **WhatsApp:** Pastikan customer memiliki nomor telepon yang valid
2. **Web Print:** Test di berbagai browser dan ukuran kertas
3. **Thermal:** Test preview di browser, implementasi Android terpisah

## Troubleshooting

- **WhatsApp tidak terbuka:** Periksa format nomor telepon customer
- **Print tidak muncul:** Periksa popup blocker browser
- **Thermal tidak berfungsi:** Pastikan interface Android sudah diimplementasi