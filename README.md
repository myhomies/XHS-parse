代码和MD均由chatgpt生成

# XHS-PARSE
本接口用于从网页中提取图片链接。

## 接口URL
GET /extract-image-urls


## 接口描述

该接口用于提取网页中的图片无水印链接。

## 请求

- 请求方法: GET
- 请求路径: `/extract-image-urls`
- 请求参数:
  - `url` (必填): 需要提取图片的网页地址，可以是原始链接或者短链接。
- 示例请求:
GET /extract-image-urls?url=https://www.xiaohongshu.com/discovery/item/XXXXXXXXXXX 

## 响应

- 响应状态码: 200 OK
- 响应内容: JSON 对象包含以下字段:
- `code`: 状态码，表示接口执行结果，0 表示成功，其他值表示失败。
- `msg`: 结果信息，如果接口执行成功，为"Success"，如果失败，为具体的错误信息。
- `imageUrls`: 图片无水印链接数组，包含提取到的图片链接。

示例响应:

```json
{
"code": 0,
"msg": "Success",
"imageUrls": [
  "https://example.com/image1.jpg",
  "https://example.com/image2.jpg",
  "https://example.com/image3.jpg"
]
}
