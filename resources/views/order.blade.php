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
                            <td width="20%" align="center">
                                <div style="font-size: 16px; font-weight: 600;">Pedido</div>
                                <div style="font-size: 16px; font-weight: 600;">{{ sprintf('%04d', $order['id']) }}</div>
                                <span style="display: block; font-size: 9px;">Emitido em {{ \Carbon\Carbon::parse(\Carbon\Carbon::now(-3))->format('d/m/Y H:i:s') }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr valign="top">
                <td style="border: none;">
                    <span style="display: block; font-size: 10px; font-weight: 600; margin-top: 5px; text-transform: uppercase;">
                        Cliente
                    </span>
                </td>
            </tr>

            <tr valign="top">
                <td class="no-padding">
                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        <tr valign="top">
                            <td width="60%">
                                <div style="font-size: 7px;">Nome/Razão Social</div>
                                <span style="display: block; font-size: 14px;">{{ $order['customer']['name'] }}</span>
                            </td>
                            <td width="20%">
                                <div style="font-size: 7px;">Código</div>
                                <span style="display: block; font-size: 14px;">{{ $order['customer']['id'] }}</span>
                            </td>
                            <td width="20%">
                                <div style="font-size: 7px;">CNPJ/CPF</div>
                                <span style="display: block; font-size: 14px;">{{ $order['customer']['cnpj'] }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr valign="top">
                <td class="no-padding">
                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        <tr valign="top">
                            <td width="80%">
                                <div style="font-size: 7px;">Nome Fantasia</div>
                                <span style="display: block; font-size: 14px;">{{ $order['customer']['name'] }}</span>
                            </td>
                            <td width="20%">
                                <div style="font-size: 7px;">Inscrição Estadual/RG</div>
                                <span style="display: block; font-size: 14px;">{{ $order['customer']['ie'] }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr valign="top">
                <td class="no-padding">
                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        <tr valign="top">
                            <td width="50%">
                                <div style="font-size: 7px;">Endereço</div>
                                <span style="display: block; font-size: 14px;">{{ $order['customer']['address'] }}</span>
                            </td>
                            <td width="37%">
                                <div style="font-size: 7px;">Bairro</div>
                                <span style="display: block; font-size: 14px;">{{ $order['customer']['district'] }}</span>
                            </td>
                            <td width="13%">
                                <div style="font-size: 7px;">CEP</div>
                                <span style="display: block; font-size: 14px;">{{ $order['customer']['cep'] }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr valign="top">
                <td class="no-padding">
                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        <tr valign="top">
                            <td width="50%">
                                <div style="font-size: 7px;">Município</div>
                                <span style="display: block; font-size: 14px;">{{ $order['customer']['city'] }}</span>
                            </td>
                            <td width="20%">
                                <div style="font-size: 7px;">Fone</div>
                                <span style="display: block; font-size: 14px;">{{ $order['customer']['phone'] }}</span>
                            </td>
                            <td width="5%">
                                <div style="font-size: 7px;">UF</div>
                                <span style="display: block; font-size: 14px;"></span>
                            </td>
                            <td width="25%">
                                <div style="font-size: 7px;">Complemento</div>
                                <span style="display: block; font-size: 14px;">&nbsp;</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr valign="top">
                <td style="border: none;">
                    <span style="display: block; font-size: 10px; font-weight: 600; margin-top: 5px; text-transform: uppercase;">
                        Valores
                    </span>
                </td>
            </tr>

            <tr valign="top">
                <td class="no-padding">
                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        <tr valign="top">
                            <td>
                                <div style="font-size: 7px;">Valor total dos produtos</div>
                                <span style="display: block; font-size: 14px; text-align: right;">{{ number_format($order['total'], 2, ',', '.') }}</span>
                            </td>
                            <td width="85">
                                <div style="font-size: 7px;">Desc./Acrés. em percentual</div>
                                <span style="display: block; font-size: 14px;">&nbsp;</span>
                            </td>
                            <td width="70">
                                <div style="font-size: 7px;">Desc./Acrés. em valor</div>
                                <span style="display: block; font-size: 14px;">&nbsp;</span>
                            </td>
                            <td width="55">
                                <div style="font-size: 7px;">Valor total do IPI</div>
                                <span style="display: block; font-size: 14px;">&nbsp;</span>
                            </td>
                            <td width="55">
                                <div style="font-size: 7px;">Valor total do ST</div>
                                <span style="display: block; font-size: 14px;">&nbsp;</span>
                            </td>
                            <td width="65">
                                <div style="font-size: 7px;">Valor total do frete</div>
                                <span style="display: block; font-size: 14px;">&nbsp;</span>
                            </td>
                            <td>
                                <div style="font-size: 7px;">Valor total do pedido</div>
                                <span style="display: block; font-size: 14px; font-weight: 600; text-align: right;">{{ number_format($order['total'], 2, ',', '.') }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr valign="top">
                <td style="border: none;">
                    <span style="display: block; font-size: 10px; font-weight: 600; margin-top: 5px; text-transform: uppercase;">
                        Outras informações
                    </span>
                </td>
            </tr>

            <tr valign="top">
                <td class="no-padding">
                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        <tr valign="top">
                            <td width="20%">
                                <div style="font-size: 7px;">Previsão de entrega</div>
                                <span style="display: block; font-size: 14px;">
                                    @if (isset($order['delivery_date']))
                                        {{ \Carbon\Carbon::parse($order['delivery_date'])->format('d/m/Y') }}
                                    @else
                                        &nbsp;
                                    @endif
                                </span>
                            </td>
                            <td width="20%">
                                <div style="font-size: 7px;">Ordem de compra</div>
                                <span style="display: block; font-size: 14px;">&nbsp;</span>
                            </td>
                            <td width="10%">
                                <div style="font-size: 7px;">Frete</div>
                                <span style="display: block; font-size: 14px;">&nbsp;</span>
                            </td>
                            <td width="50%">
                                <div style="font-size: 7px;">Transportadora</div>
                                <span style="display: block; font-size: 14px;">&nbsp;</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr valign="top">
                <td class="no-padding">
                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        <tr valign="top">
                            <td width="50%">
                                <div style="font-size: 7px;">Tabela de preço</div>
                                <span style="display: block; font-size: 14px;">&nbsp;</span>
                            </td>
                            <td width="40%">
                                <div style="font-size: 7px;">Forma de pagamento</div>
                                <span style="display: block; font-size: 14px;">
                                    @switch($order['payment_method'])
                                        @case(1) Boleto @break
                                        @case(2) Cheque @break
                                        @case(3) Dinheiro @break
                                        @default Voucher
                                    @endswitch
                                </span>
                            </td>
                            <td width="10%">
                                <div style="font-size: 7px;">Peso em KG</div>
                                <span style="display: block; font-size: 14px;">{{ $weight }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr valign="top">
                <td class="no-padding">
                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        <tr valign="top">
                            <td width="100%">
                                <div style="font-size: 7px;">Condição de pagamento</div>
                                <span style="display: block; font-size: 14px;">
                                    @if (isset($order['payment_date']))
                                        @if (isset($order['delivery_date']))
                                            ({{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order['payment_date'])->diffInDays(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order['delivery_date'])) }})
                                        @endif

                                        {{ \Carbon\Carbon::parse($order['payment_date'])->format('d/m/Y') }}
                                    @else
                                        &nbsp;
                                    @endif
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr valign="top">
                <td class="no-padding">
                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        <tr valign="top">
                            <td width="100%">
                                <div style="font-size: 7px;">Observação</div>
                                <span style="display: block; font-size: 14px;">
                                    @if(!empty($order['comments']))
                                        {{ $order['comments'] }}
                                    @else
                                        &nbsp;
                                    @endif
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr valign="top">
                <td class="no-padding">
                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        <tr valign="top">
                            <td width="50%">
                                <div style="font-size: 7px;">E-mail para envio da cópia do pedido</div>
                                <span style="display: block; font-size: 14px;">&nbsp;</span>
                            </td>
                            <td width="50%">
                                <div style="font-size: 7px;">E-mail para envio do XML da NFE</div>
                                <span style="display: block; font-size: 14px;">&nbsp;</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr valign="top">
                <td style="border: none;">
                    <span style="display: block; font-size: 10px; font-weight: 600; margin-top: 5px; text-transform: uppercase;">
                        Dados do produto
                    </span>
                </td>
            </tr>

            <tr valign="top">
                <td class="no-padding">
                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        @foreach ($order['order_product'] as $product)
                            @if ($loop->index > 0 && ($loop->index == 30 || ($loop->index - 30) % 60 == 0))
                    </table>

                    <div class="page-break"></div>

                    <table width="100%" align="center" cellpadding="0" cellspacing="0">
                        @endif

                        @if ($loop->index == 0 || $loop->index == 30 || ($loop->index - 30) % 60 == 0)
                            <tr valign="top">
                                <td style="border-bottom: 1px solid #000; border-top: 1px solid #000;" width="60">
                                    <div style="font-size: 7px;">Código produto</div>
                                </td>
                                <td style="border-bottom: 1px solid #000; border-top: 1px solid #000;" align="center">
                                    <div style="font-size: 7px;">Descrição do produto</div>
                                </td>
                                <td style="border-bottom: 1px solid #000; border-top: 1px solid #000;" align="center" width="30">
                                    <div style="font-size: 7px;">NCM</div>
                                </td>
                                <td style="border-bottom: 1px solid #000; border-top: 1px solid #000;" width="30">
                                    <div style="font-size: 7px;">Unid.</div>
                                </td>
                                <td style="border-bottom: 1px solid #000; border-top: 1px solid #000;" align="right" width="30">
                                    <div style="font-size: 7px;">Quant.</div>
                                </td>
                                <td style="border-bottom: 1px solid #000; border-top: 1px solid #000;" align="right" width="50">
                                    <div style="font-size: 7px;">Valor unit.</div>
                                </td>
                                <td style="border-bottom: 1px solid #000; border-top: 1px solid #000;" align="right" width="50">
                                    <div style="font-size: 7px;">Valor total</div>
                                </td>
                                <td style="border-bottom: 1px solid #000; border-top: 1px solid #000;" align="right" width="20">
                                    <div style="font-size: 7px;">IPI</div>
                                </td>
                                <td style="border-bottom: 1px solid #000; border-top: 1px solid #000;" width="50">
                                    <div style="font-size: 7px;">Código de barra</div>
                                </td>
                            </tr>
                        @endif
                        <tr valign="top">
                            <td style="border-top: 0; border-bottom: 0; border-left: 0;">
                                <div style="font-size: 9px;">{{ sprintf('%04d', $product['product']['id']) }}</div>
                            </td>
                            <td style="border-top: 0; border-bottom: 0; border-left: 0;">
                                <div style="font-size: 9px;">{{ $product['product']['name'] }}</div>
                            </td>
                            <td style="border-top: 0; border-bottom: 0; border-left: 0;" align="center">
                                <div style="font-size: 9px;">&nbsp;</div>
                            </td>
                            <td style="border-top: 0; border-bottom: 0; border-left: 0;">
                                <div style="font-size: 9px;">
                                    PCT/1
                                </div>
                            </td>
                            <td style="border-top: 0; border-bottom: 0; border-left: 0;" align="right">
                                <div style="font-size: 9px;">
                                    {{ number_format($product['quantity'], 3, ',', '.') }}
                                </div>
                            </td>
                            <td style="border-top: 0; border-bottom: 0; border-left: 0;" align="right">
                                <div style="font-size: 9px;">{{ number_format($product['unit_price'], 2, ',', '.') }}</div>
                            </td>
                            <td style="border-top: 0; border-bottom: 0; border-left: 0;" align="right">
                                <div style="font-size: 9px;">
                                    R$ {{ number_format($product['quantity'] * $product['unit_price'], 2, ',', '.') }}</div>
                            </td>
                            <td style="border-top: 0; border-bottom: 0; border-left: 0;" align="center">
                                <div style="font-size: 9px;">0,00</div>
                            </td>
                            <td style="border-top: 0; border-bottom: 0; border-left: 0;">
                                <div style="font-size: 9px;">&nbsp;</div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
