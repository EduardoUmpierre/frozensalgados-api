<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Pedido</title>

        <style>
            @page {
                font-size: 7px;
                margin: 5mm;
            }

            .page-break {
                page-break-after: always;
            }

            html,
            body {
                font-family: Arial, Tahoma, sans-serif;
            }

            table {
                border-collapse: collapse;
                font-size: 2rem;
            }

            table td {
                border: 1px solid #000;
                border-collapse: collapse;
                padding: 2px;
            }

            table td.no-padding {
                padding: 0;
            }

            table td > table td {
                border-bottom: 0;
                border-top: 0;
                padding: 2px 5px;
            }

            table td > table td:first-child {
                border-left: 0;
            }

            table td > table td:last-child {
                border-right: 0;
            }

            div {
                text-transform: uppercase;
            }
        </style>
    </head>
    <body>
        <table width="100%" align="center" cellpadding="0" cellspacing="0">
            <tr valign="top">
                <td class="no-padding">
                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        <tr valign="top">
                            <td width="20%"></td>
                            <td width="60%" align="center">
                                <div style="font-size: 16px; font-weight: 600;">CBJ ALIMENTOS LTDA - FROZEN SALGADOS</div>
                                <span style="display: block; font-size: 14px;">Demétrius Vianna</span>
                            </td>
                            <td width="20%"></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr valign="top">
                <td style="border: none;">
                    <span style="display: block; font-size: 10px; font-weight: 600; margin-top: 5px; text-transform: uppercase;">
                        Relatório
                    </span>
                </td>
            </tr>

            <tr valign="top">
                <td class="no-padding">
                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        <tr valign="top">
                            <td>
                                <div style="font-size: 7px;">Tipo</div>
                                <span style="display: block; font-size: 14px;">
                                    {{ $category }}
                                </span>
                            </td>
                            <td width="85">
                                <div style="font-size: 7px;">De</div>
                                <span style="display: block; font-size: 14px;">
                                    {{ isset($from) && !empty($from) ? \Carbon\Carbon::parse($from)->format('d/m/Y') : 'Sempre' }}
                                </span>
                            </td>
                            <td width="70">
                                <div style="font-size: 7px;">Até</div>
                                <span style="display: block; font-size: 14px;">
                                    {{ isset($to) && !empty($to) ? \Carbon\Carbon::parse($to)->format('d/m/Y') : 'Sempre' }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 7px;">Valor total</div>
                                <span style="display: block; font-size: 14px; font-weight: 600; text-align: right;">{{ number_format($report['total'], 2, ',', '.') }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr valign="top">
                <td style="border: none;">
                    <span style="display: block; font-size: 10px; font-weight: 600; margin-top: 5px; text-transform: uppercase;">
                        Dados
                    </span>
                </td>
            </tr>

            <tr valign="top">
                <td class="no-padding">
                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        @foreach ($report['list'] as $item)
                            @if ($loop->index > 0 && ($loop->index == 30 || ($loop->index - 30) % 60 == 0))
                    </table>

                    <div class="page-break"></div>

                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        @endif

                        @if ($loop->index == 0 || $loop->index == 30 || ($loop->index - 30) % 60 == 0)
                            <tr valign="top">
                                <td style="border-bottom: 1px solid #000; border-top: 1px solid #000;" width="60">
                                    <div style="font-size: 7px;">Identificador</div>
                                </td>
                                <td style="border-bottom: 1px solid #000; border-top: 1px solid #000;">
                                    <div style="font-size: 7px;">Nome</div>
                                </td>
                                @if (isset($item['quantity']))
                                    <td style="border-bottom: 1px solid #000; border-top: 1px solid #000;" width="30">
                                        <div style="font-size: 7px;">Quant.</div>
                                    </td>
                                @else
                                    <td style="border-bottom: 1px solid #000; border-top: 1px solid #000;" width="80">
                                        <div style="font-size: 7px;">Data</div>
                                    </td>
                                @endif
                                <td style="border-bottom: 1px solid #000; border-top: 1px solid #000;" width="80">
                                    <div style="font-size: 7px;">Total</div>
                                </td>
                            </tr>
                        @endif
                        <tr valign="top">
                            <td style="border-top: 0; border-bottom: 0; border-left: 0;">
                                <div style="font-size: 9px;">{{ sprintf('%04d', $item['id']) }}</div>
                            </td>
                            <td style="border-top: 0; border-bottom: 0; border-left: 0;">
                                <div style="font-size: 9px;">{{ $item['name'] }}</div>
                            </td>
                            @if (isset($item['quantity']))
                                <td style="border-top: 0; border-bottom: 0; border-left: 0;">
                                    <div style="font-size: 9px;">
                                        {{ number_format($item['quantity'], 3, ',', '.') }}
                                    </div>
                                </td>
                            @else
                                <td style="border-top: 0; border-bottom: 0; border-left: 0;">
                                    <div style="font-size: 9px;">
                                        {{ \Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i:s') }}
                                    </div>
                                </td>
                            @endif
                            <td style="border-top: 0; border-bottom: 0; border-left: 0;">
                                <div style="font-size: 9px;">
                                    R$ {{ number_format($item['total'], 2, ',', '.') }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
