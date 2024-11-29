[{
  "relation": ["delegate_permission/common.handle_all_urls"],
  "target": {
    "namespace": "android_app",
    "package_name": "com.madrasahdigital.walisantri.ppi67benda",
    "sha256_cert_fingerprints": ["75:C6:DB:CC:94:56:0F:ED:A4:BD:7D:86:C6:8E:C4:77:3B:7F:1C:03:A5:45:9F:DA:2D:7F:CF:56:4A:37:72:47"]
  }
},{
    "relation": ["delegate_permission/common.handle_all_urls"],
    "target": {
      "namespace": "android_app",
      "package_name": "com.madrasahdigital.walisantri.ppi67benda",
      "sha256_cert_fingerprints":
        ["DD:D6:97:5A:C1:26:E2:A6:18:AC:4C:AA:DD:7E:64:E3:32:B0:7C:7F:AA:6A:A6:13:15:EF:6E:10:09:75:81:32"]
    }
}]
