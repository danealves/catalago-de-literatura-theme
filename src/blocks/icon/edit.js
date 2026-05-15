/**
 * Block: cl/icon — Edit component
 *
 * Provides the editor interface with:
 *   - InspectorControls to switch between Dashicon / Media
 *   - Searchable Dashicon picker grid
 *   - MediaUpload for custom images
 *   - Size (RangeControl) and Color (ColorPalette) controls
 */
import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import {
	PanelBody,
	Button,
	ButtonGroup,
	RangeControl,
	ColorPalette,
	TextControl,
	Placeholder,
} from '@wordpress/components';
import { useState, useMemo } from '@wordpress/element';
import { DASHICONS } from './dashicons-list';

export default function Edit( { attributes, setAttributes } ) {
	const {
		source,
		dashicon,
		iconColor,
		mediaId,
		mediaUrl,
		mediaAlt,
		size,
	} = attributes;

	const [ search, setSearch ] = useState( '' );

	const filteredIcons = useMemo( () => {
		if ( ! search ) {
			return DASHICONS;
		}
		const term = search.toLowerCase();
		return DASHICONS.filter(
			( i ) =>
				i.slug.includes( term ) ||
				i.label.toLowerCase().includes( term )
		);
	}, [ search ] );

	const isSvg = mediaUrl && mediaUrl.toLowerCase().endsWith( '.svg' );

	const blockProps = useBlockProps( {
		className: 'cl-icon-block',
	} );

	/* ── Preview inside the editor ── */
	const renderPreview = () => {
		if ( source === 'dashicon' ) {
			return (
				<span
					className={ `dashicons dashicons-${ dashicon }` }
					style={ {
						fontSize: `${ size }px`,
						width: `${ size }px`,
						height: `${ size }px`,
						lineHeight: `${ size }px`,
						color: iconColor,
					} }
					aria-hidden="true"
				/>
			);
		}

		if ( source === 'media' && mediaUrl ) {
			// SVG with color applied → use CSS mask-image to colorize
			if ( isSvg && iconColor ) {
				return (
					<span
						className="cl-icon-block__svg-mask"
						style={ {
							width: `${ size }px`,
							height: `${ size }px`,
							backgroundColor: iconColor,
							WebkitMaskImage: `url(${ mediaUrl })`,
							maskImage: `url(${ mediaUrl })`,
							WebkitMaskSize: 'contain',
							maskSize: 'contain',
							WebkitMaskRepeat: 'no-repeat',
							maskRepeat: 'no-repeat',
							WebkitMaskPosition: 'center',
							maskPosition: 'center',
						} }
						aria-hidden="true"
					/>
				);
			}

			// Raster image or SVG without color → regular img
			return (
				<img
					src={ mediaUrl }
					alt={ mediaAlt }
					style={ {
						width: `${ size }px`,
						height: `${ size }px`,
						objectFit: 'contain',
					} }
				/>
			);
		}

		return (
			<Placeholder
				icon="format-image"
				label={ __( 'Ícone', 'catalago-de-literatura' ) }
				instructions={ __(
					'Selecione uma imagem no painel lateral.',
					'catalago-de-literatura'
				) }
			/>
		);
	};

	return (
		<>
			<InspectorControls>
				{ /* ── Source toggle ── */ }
				<PanelBody
					title={ __( 'Fonte do Ícone', 'catalago-de-literatura' ) }
					initialOpen={ true }
				>
					<ButtonGroup className="cl-icon-source-toggle">
						<Button
							variant={
								source === 'dashicon' ? 'primary' : 'secondary'
							}
							onClick={ () =>
								setAttributes( { source: 'dashicon' } )
							}
						>
							{ __( 'Dashicon', 'catalago-de-literatura' ) }
						</Button>
						<Button
							variant={
								source === 'media' ? 'primary' : 'secondary'
							}
							onClick={ () =>
								setAttributes( { source: 'media' } )
							}
						>
							{ __( 'Imagem', 'catalago-de-literatura' ) }
						</Button>
					</ButtonGroup>

					{ /* ── Dashicon picker ── */ }
					{ source === 'dashicon' && (
						<div className="cl-icon-picker">
							<TextControl
								label={ __(
									'Buscar ícone',
									'catalago-de-literatura'
								) }
								value={ search }
								onChange={ setSearch }
								placeholder={ __(
									'Ex: heart, book, star…',
									'catalago-de-literatura'
								) }
							/>

							<div className="cl-icon-picker__grid">
								{ filteredIcons.map( ( icon ) => (
									<button
										key={ icon.slug }
										type="button"
										className={ `cl-icon-picker__item ${
											dashicon === icon.slug
												? 'is-selected'
												: ''
										}` }
										onClick={ () =>
											setAttributes( {
												dashicon: icon.slug,
											} )
										}
										title={ icon.label }
										aria-label={ icon.label }
									>
										<span
											className={ `dashicons dashicons-${ icon.slug }` }
										/>
									</button>
								) ) }
							</div>

							{ filteredIcons.length === 0 && (
								<p className="cl-icon-picker__empty">
									{ __(
										'Nenhum ícone encontrado.',
										'catalago-de-literatura'
									) }
								</p>
							) }
						</div>
					) }

					{ /* ── Media upload ── */ }
					{ source === 'media' && (
						<div className="cl-icon-media">
							<MediaUploadCheck>
								<MediaUpload
									onSelect={ ( media ) =>
										setAttributes( {
											mediaId: media.id,
											mediaUrl: media.url,
											mediaAlt: media.alt || '',
										} )
									}
									allowedTypes={ [ 'image' ] }
									value={ mediaId }
									render={ ( { open } ) => (
										<div className="cl-icon-media__controls">
											{ mediaUrl && (
												<img
													src={ mediaUrl }
													alt={ mediaAlt }
													className="cl-icon-media__preview"
												/>
											) }
											<Button
												variant="secondary"
												onClick={ open }
												className="cl-icon-media__btn"
											>
												{ mediaUrl
													? __(
															'Trocar imagem',
															'catalago-de-literatura'
													  )
													: __(
															'Selecionar imagem',
															'catalago-de-literatura'
													  ) }
											</Button>
											{ mediaUrl && (
												<Button
													variant="link"
													isDestructive
													onClick={ () =>
														setAttributes( {
															mediaId: 0,
															mediaUrl: '',
															mediaAlt: '',
														} )
													}
												>
													{ __(
														'Remover',
														'catalago-de-literatura'
													) }
												</Button>
											) }
										</div>
									) }
								/>
							</MediaUploadCheck>
						</div>
					) }
				</PanelBody>

				{ /* ── Size ── */ }
				<PanelBody
					title={ __( 'Tamanho', 'catalago-de-literatura' ) }
					initialOpen={ true }
				>
					<RangeControl
						label={ __( 'Tamanho (px)', 'catalago-de-literatura' ) }
						value={ size }
						onChange={ ( v ) => setAttributes( { size: v } ) }
						min={ 16 }
						max={ 200 }
						step={ 2 }
					/>
				</PanelBody>

				{ /* ── Color (Dashicon + SVG) ── */ }
				{ ( source === 'dashicon' || ( source === 'media' && isSvg ) ) && (
					<PanelBody
						title={ __( 'Cor', 'catalago-de-literatura' ) }
						initialOpen={ false }
					>
						{ source === 'media' && (
							<p className="components-base-control__help">
								{ __(
									'Aplica cor ao SVG usando máscara CSS.',
									'catalago-de-literatura'
								) }
							</p>
						) }
						<ColorPalette
							value={ iconColor }
							onChange={ ( v ) =>
								setAttributes( {
									iconColor: v || '',
								} )
							}
						/>
					</PanelBody>
				) }
			</InspectorControls>

			<div { ...blockProps }>{ renderPreview() }</div>
		</>
	);
}
