particlesJS("confetti",{
	"particles":{
		"number":{
			"value":500, // 数
			"density":{
				"enable":true, // 密集度を変更する
				"value_area":600 // 密集度
			}
		},
		"color": {
            "value": ["#ff0000","#ffa500","#ffff00","#008000","#00ffff","#0000ff","#800080"] // 赤、オレンジ、黄、緑、アクア、青、紫
		},
		"shape":{
			"type":"edge", // 四角
			"stroke":{
				"width":0, // 外線なし
			}
			},
			"opacity":{
				"value":1, // 透明度
				"random":false, // 透明度をランダムにしない
				"anim":{
					"enable":true, // 透明度をアニメーションさせる
					"speed":20, // アニメーションの速度
					"opacity_min":0.1, // 透明度の最小値
					"sync":false // アニメーションを同期させない
				}
			},
			"size":{
				"value":5, // 大きさ
				"random":true, // 大きさをランダムにする
				"anim":{
					"enable":false, // 大きさをアニメーションさせない
				}
			},
			"line_linked":{
                "enable":false, // 紙吹雪をつなぐ線を表示しない
			},
			"move":{
                "speed":8, // 移動するスピード
                "straight":false, // 動きを止めない
                "direction":"bottom", // 下に向かって落ちる
                "out_mode":"out" // エリア外に出た際はそのまま(跳ね返らず)出る
			}
		},
		"interactivity":{
			"detect_on":"canvas",
			"events":{
				"onhover":{
					"enable":false, // マウスオーバー無効
				},
				"onclick":{
					"enable":false, // クリック無効
				},
			}
		},
		"retina_detect":true, // Retina Displayに対応する
        "resize":true //canvasのサイズ変更にわせて拡大縮小する
	});