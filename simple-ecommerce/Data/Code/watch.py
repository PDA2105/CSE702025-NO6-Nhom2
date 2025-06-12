import requests
from bs4 import BeautifulSoup
import csv

url = 'https://shopdunk.com/apple-watch'
headers = {'User-Agent': 'Mozilla/5.0'}

try:
    response = requests.get(url, headers=headers)
    response.raise_for_status()
    soup = BeautifulSoup(response.text, 'html.parser')
    products = soup.select('div.product-item')

    data = []

    for product in products:
        ten_san_pham_tag = product.select_one('h3.product-title a')
        ten_san_pham = ten_san_pham_tag.text.strip() if ten_san_pham_tag else ''

        mo_ta = ''

        gia_goc_tag = product.select_one('span.old-price')
        gia_goc = gia_goc_tag.text.strip() if gia_goc_tag else ''
        gia_goc = gia_goc.replace('₫', '').strip() if gia_goc else ''
        gia_goc = gia_goc.replace('.', '').strip() if gia_goc else ''

        gia_khuyen_mai_tag = product.select_one('span.actual-price')
        gia_khuyen_mai = gia_khuyen_mai_tag.text.strip() if gia_khuyen_mai_tag else ''
        gia_khuyen_mai = gia_khuyen_mai.replace('₫', '').strip() if gia_khuyen_mai else ''
        gia_khuyen_mai = gia_khuyen_mai.replace('.', '').strip() if gia_khuyen_mai else ''

        danh_muc = 'watch'

        ton_kho = '100'
        sku = ''  # Giá trị SKU mặc định là rỗng

        img_tag = product.select_one('.picture img')
        img_url = ''
        if img_tag:
            img_url = img_tag.get('data-src') or img_tag.get('src')
            if img_url:
                img_url = img_url.strip()
                if img_url.startswith("//"):
                    img_url = "https:" + img_url
                elif img_url.startswith("/"):
                    img_url = "https://shopdunk.com" + img_url

        data.append({
            'name': ten_san_pham,
            'description': mo_ta,
            'regular_price': gia_goc,
            'sale_price': gia_khuyen_mai,
            'stock': ton_kho,
            'sku': sku,
            'category': danh_muc,
            'image_url': img_url,
            
        })

    with open('watch.csv', 'w', newline='', encoding='utf-8-sig') as file:
        fieldnames = ['name', 'description', 'regular_price', 'sale_price', 'stock','sku', 'category', 'image_url']
        writer = csv.DictWriter(file, fieldnames=fieldnames)
        writer.writeheader()
        writer.writerows(data)

    print("✅ Đã xuất dữ liệu vào file 'watch.csv'")

except requests.exceptions.RequestException as e:
    print(f"❌ Lỗi khi kết nối đến trang web: {e}")
    exit()
