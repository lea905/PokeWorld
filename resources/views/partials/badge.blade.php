{{--
    Badge d'une arène : image si disponible, sinon losange avec l'initiale.
    Paramètres : $arene, $imgStyle (style de l'image), $size (taille du losange : 'sm' ou 'lg').
--}}
@php($size = $size ?? 'sm')
@if ($arene->badge_est_image)
    <img src="{{ asset('images/Badges/'.$arene->badge) }}" alt="Badge" class="img-fluid {{ $imgClass ?? '' }}" style="{{ $imgStyle }}">
@elseif ($arene->image_badge)
    <img src="{{ asset($arene->image_badge) }}" alt="Badge" class="img-fluid {{ $imgClass ?? '' }}" style="{{ $imgStyle }}">
@elseif ($size === 'lg')
    <div class="position-relative d-flex align-items-center justify-content-center shadow-lg mx-auto mb-4"
         style="width: 140px; height: 140px; background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%); border-radius: 30px; transform: rotate(45deg); border: 8px solid #fef3c7;">
        <div style="transform: rotate(-45deg); color: #fff; font-weight: 900; font-size: 4.5rem; text-shadow: 0 4px 8px rgba(0,0,0,0.3);">
            {{ $arene->badge ? mb_strtoupper(mb_substr($arene->badge, 0, 1)) : '?' }}
        </div>
    </div>
@else
    <div class="position-relative d-flex align-items-center justify-content-center shadow-lg"
         style="width: 65px; height: 65px; background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%); border-radius: 16px; transform: rotate(45deg); border: 4px solid #fef3c7;">
        <div style="transform: rotate(-45deg); color: #fff; font-weight: 900; font-size: 1.8rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
            {{ $arene->badge ? mb_strtoupper(mb_substr($arene->badge, 0, 1)) : '?' }}
        </div>
    </div>
@endif
