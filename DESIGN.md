---
name: Restaurant management
description: Dark warm UI, Cairo type, amber accent, WhatsApp green for actions
colors:
  surface-0: "#0e0f12"
  surface-1: "#13151a"
  surface-2: "#1a1d23"
  surface-3: "#22262e"
  text-primary: "#f0ece3"
  text-muted: "#9a9690"
  text-dim: "#5c5955"
  accent-amber: "#f5a623"
  accent-amber-deep: "#d97706"
  success-green: "#25d366"
  border-subtle: "rgba(255,255,255,0.08)"
typography:
  display:
    fontFamily: "Cairo, system-ui, sans-serif"
    fontSize: "clamp(2.5rem, 6vw, 5rem)"
    fontWeight: 900
    lineHeight: 1.12
    letterSpacing: "-0.04em"
  body:
    fontFamily: "Cairo, system-ui, sans-serif"
    fontSize: "16px"
    fontWeight: 400
    lineHeight: 1.65
  label:
    fontFamily: "Cairo, system-ui, sans-serif"
    fontSize: "11px"
    fontWeight: 700
    letterSpacing: "0.12em"
    lineHeight: 1.3
rounded:
  sm: "8px"
  md: "12px"
  lg: "16px"
  xl: "20px"
spacing:
  xs: "6px"
  sm: "12px"
  md: "20px"
  lg: "32px"
  section: "120px"
components:
  button-primary:
    backgroundColor: "{colors.accent-amber}"
    textColor: "#1a1408"
    rounded: "{rounded.sm}"
    padding: "10px 22px"
  button-primary-hover:
    backgroundColor: "#fbbf24"
    textColor: "#1a1408"
    rounded: "{rounded.sm}"
    padding: "10px 22px"
---

## Overview

نظام بصري داكن دافئ لمطاعم عربية: أسطح «حبر دافئ» مائلة للعنبر، نص كريمي، وعنبر للتأكيد والعلامة. الأخضر يُستخدم لمسارات النجاح والطلب عبر واتساب. الهدف إحساس مطبخ حديث وليس لوحة مراقبة تقنية باردة.

## Colors

الأساسيات: سلسلة `surface-*` من أغمق للأفتح. العنبر `accent-amber` للروابط والأزرار الرئيسية والأسعار. `success-green` للحالات الإيجابية وشريط السلة. تجنب `#000` و`#fff` الصِرف؛ النصوص على الأزرار داكنة دافئة (`#1a1408`).

## Typography

Cairo لكل الواجهات. العناوين الكبيرة: وزن 800–900 وتتبع ضيق. الجسم 16px تقريباً، تباين هرمي ≥1.25 بين مستويات العناوين والنص الثانوي.

## Elevation

ظلال خفيفة `shadow-black/20` على البطاقات؛ توهج عنبر خفيف جداً حول العناصر التفاعلية فقط، دون هالات ثقيلة على كل الشاشة.

## Components

أزرار أساسية: تعبئة عنبر صلبة أو حدود شفافة مع hover يزيد التباين. حقول: `surface-2`، حدود `border-subtle`، تركيز بحد عنبر شفاف. الشريط الجانبي للوحة التحكم: سطح `surface-1` مع فصل بحد علوي/سففي أو شفافية منخفضة، بدون شريط جانبي ملون عريض كزخرفة.

## Do's and Don'ts

- **Do:** توحيد المتغيرات عبر `tokens.css`، تمييز صفوف الإحصاء بأوزان مختلفة بدل أربع بطاقات مطابقة، `cubic-bezier` ease-out للانتقالات.
- **Don't:** `background-clip: text` بتدرج، شبكة بطاقات أيقونة+عنوان+نص متطابقة، حركة ارتدادية على عناصر الواجهة، زجاجية blur على كل شريط تنقل.
