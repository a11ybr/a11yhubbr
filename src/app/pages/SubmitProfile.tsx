import { useState } from "react";
import { Link } from "react-router";
import {
  CheckCircle,
  Plus,
  Trash,
  UserPlus,
  Upload,
  X,
} from "lucide-react";
import { Breadcrumb } from "../components/Breadcrumb";

export function SubmitProfile() {
  const [submitted, setSubmitted] = useState(false);
  const [formData, setFormData] = useState({
    type: "",
    name: "",
    role: "",
    location: "",
    description: "",
    website: "",
    email: "",
    socialLinks: [] as Array<{ platform: string; url: string }>,
    profileImage: null as File | null,
  });
  const [imagePreview, setImagePreview] = useState<string | null>(null);
  const [imageError, setImageError] = useState<string>("");

  const profileTypes = [
    {
      value: "profissional-tech",
      label: "Profissional de tecnologia",
    },
    { value: "empresa", label: "Empresa ou ONG" },
    { value: "interprete", label: "Intérprete de Libras" },
    { value: "audiodescritor", label: "Audiodescritor" },
    { value: "tradutor-braille", label: "Tradutor de Braille" },
  ];

  const socialPlatforms = [
    { value: "linkedin", label: "LinkedIn" },
    { value: "instagram", label: "Instagram" },
    { value: "facebook", label: "Facebook" },
    { value: "twitter", label: "X / Twitter" },
    { value: "tiktok", label: "TikTok" },
    { value: "youtube", label: "YouTube" },
    { value: "github", label: "GitHub" },
    { value: "behance", label: "Behance" },
    { value: "dribbble", label: "Dribbble" },
    { value: "medium", label: "Medium" },
    { value: "outro", label: "Outro" },
  ];

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitted(true);
  };

  const handleChange = (
    e: React.ChangeEvent<
      HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement
    >,
  ) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const addSocialLink = () => {
    setFormData((prev) => ({
      ...prev,
      socialLinks: [...prev.socialLinks, { platform: "", url: "" }],
    }));
  };

  const removeSocialLink = (index: number) => {
    setFormData((prev) => ({
      ...prev,
      socialLinks: prev.socialLinks.filter((_, i) => i !== index),
    }));
  };

  const updateSocialLink = (
    index: number,
    field: "platform" | "url",
    value: string,
  ) => {
    setFormData((prev) => ({
      ...prev,
      socialLinks: prev.socialLinks.map((link, i) =>
        i === index ? { ...link, [field]: value } : link,
      ),
    }));
  };

  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    setImageError("");

    if (!file) {
      setFormData((prev) => ({ ...prev, profileImage: null }));
      setImagePreview(null);
      return;
    }

    const validTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
    if (!validTypes.includes(file.type)) {
      setImageError("Formato inválido. Use apenas JPG, PNG ou WebP.");
      e.target.value = "";
      return;
    }

    const maxSize = 2 * 1024 * 1024;
    if (file.size > maxSize) {
      setImageError("Imagem muito grande. O tamanho máximo é 2MB.");
      e.target.value = "";
      return;
    }

    setFormData((prev) => ({ ...prev, profileImage: file }));

    const reader = new FileReader();
    reader.onloadend = () => {
      setImagePreview(reader.result as string);
    };
    reader.readAsDataURL(file);
  };

  const removeImage = () => {
    setFormData((prev) => ({ ...prev, profileImage: null }));
    setImagePreview(null);
    setImageError("");
  };

  if (submitted) {
    return (
      <div className="flex-1 flex items-center justify-center py-20">
        <div className="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <div className="bg-green-100 text-green-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <CheckCircle className="w-12 h-12" aria-hidden="true" />
          </div>
          <h1 className="text-3xl md:text-4xl mb-4">Perfil criado com sucesso!</h1>
          <p className="text-lg text-muted-foreground mb-8">
            Bem-vindo à comunidade a11yBR! Seu perfil será revisado e publicado em breve.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              to="/comunidade"
              className="inline-block bg-primary text-primary-foreground px-6 py-3 rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 text-center"
            >
              Ver comunidade
            </Link>
            <Link
              to="/"
              className="inline-block bg-transparent border-2 border-primary text-primary px-6 py-3 rounded-lg hover:bg-primary hover:text-primary-foreground transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 text-center"
            >
              Voltar para home
            </Link>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="flex-1">
      <section className="bg-primary text-primary-foreground py-12 md:py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <Breadcrumb
            items={[
              { label: "Submeter", href: "/submeter" },
              { label: "Perfil" },
            ]}
          />
          <div className="flex items-center gap-4 mb-6">
            <UserPlus className="w-12 h-12" aria-hidden="true" />
            <h1 className="text-4xl md:text-5xl">Submeter perfil</h1>
          </div>
          <p className="text-xl text-primary-foreground/90 max-w-2xl">
            Cadastre seu perfil profissional ou institucional para fazer parte do diretório da comunidade.
          </p>
        </div>
      </section>

      <section className="py-12 md:py-16 bg-background bg-[#ffffffed]">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div className="lg:col-span-2">
              <form onSubmit={handleSubmit} className="space-y-8">
                <div className="border border-border rounded-lg p-6 bg-[#ffffff]">
                  <h2 className="text-2xl mb-6">Detalhes do perfil</h2>

                  <div className="mb-6">
                    <label htmlFor="type" className="block mb-2">
                      Tipo de perfil <span className="text-destructive" aria-label="obrigatório">*</span>
                    </label>
                    <select
                      id="type"
                      name="type"
                      value={formData.type}
                      onChange={handleChange}
                      required
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                      aria-required="true"
                    >
                      <option value="">Selecione o tipo</option>
                      {profileTypes.map((type) => (
                        <option key={type.value} value={type.value}>
                          {type.label}
                        </option>
                      ))}
                    </select>
                  </div>

                  <div className="mb-6">
                    <label htmlFor="name" className="block mb-2">
                      Nome ou nome da organização <span className="text-destructive" aria-label="obrigatório">*</span>
                    </label>
                    <input
                      type="text"
                      id="name"
                      name="name"
                      value={formData.name}
                      onChange={handleChange}
                      required
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                      placeholder="Nome ou nome da organização"
                      aria-required="true"
                    />
                  </div>

                  <div className="mb-6">
                    <label htmlFor="location" className="block mb-2">
                      Localização (cidade, estado) <span className="text-destructive" aria-label="obrigatório">*</span>
                    </label>
                    <input
                      type="text"
                      id="location"
                      name="location"
                      value={formData.location}
                      onChange={handleChange}
                      required
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                      placeholder="Localização (cidade, estado)"
                      aria-required="true"
                    />
                  </div>

                  <div className="mb-0">
                    <label htmlFor="description" className="block mb-2">
                      Bio profissional ou descrição institucional <span className="text-destructive" aria-label="obrigatório">*</span>
                    </label>
                    <textarea
                      id="description"
                      name="description"
                      value={formData.description}
                      onChange={handleChange}
                      required
                      rows={5}
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                      placeholder="Bio profissional ou descrição institucional"
                      aria-required="true"
                    />
                  </div>
                </div>

                <div className="border border-border rounded-lg p-6 bg-[#ffffff]">
                  <h2 className="text-2xl mb-6">Especializações ou áreas de atuação</h2>

                  <div className="mb-6">
                    <label htmlFor="role" className="block mb-2">
                      Cargo / Especialidade <span className="text-destructive" aria-label="obrigatório">*</span>
                    </label>
                    <input
                      type="text"
                      id="role"
                      name="role"
                      value={formData.role}
                      onChange={handleChange}
                      required
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                      placeholder="Cargo / Especialidade"
                      aria-required="true"
                    />
                  </div>

                  <div className="mb-6">
                    <label htmlFor="website" className="block mb-2">Website</label>
                    <input
                      type="url"
                      id="website"
                      name="website"
                      value={formData.website}
                      onChange={handleChange}
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                      placeholder="https://seusite.com"
                    />
                    <p className="text-sm text-muted-foreground mt-2">
                      Seu site pessoal, portfolio ou página profissional.
                    </p>
                  </div>

                  <div className="mb-6">
                    <h3 className="text-lg mb-4">Redes sociais</h3>

                    {formData.socialLinks.length > 0 && (
                      <div className="space-y-4 mb-4">
                        {formData.socialLinks.map((link, index) => (
                          <div key={index} className="flex gap-3">
                            <select
                              value={link.platform}
                              onChange={(e) =>
                                updateSocialLink(index, "platform", e.target.value)
                              }
                              className="flex-shrink-0 w-40 px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                              aria-label={`Plataforma ${index + 1}`}
                            >
                              <option value="">Plataforma</option>
                              {socialPlatforms.map((platform) => (
                                <option key={platform.value} value={platform.value}>
                                  {platform.label}
                                </option>
                              ))}
                            </select>
                            <input
                              type="url"
                              value={link.url}
                              onChange={(e) =>
                                updateSocialLink(index, "url", e.target.value)
                              }
                              className="flex-1 px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                              placeholder="https://"
                              aria-label={`URL da rede social ${index + 1}`}
                            />
                            <button
                              type="button"
                              onClick={() => removeSocialLink(index)}
                              className="flex-shrink-0 text-destructive hover:text-destructive/80 transition-colors p-3 focus:outline-none focus:ring-2 focus:ring-primary rounded-lg"
                              aria-label={`Remover rede social ${index + 1}`}
                            >
                              <Trash className="w-5 h-5" aria-hidden="true" />
                            </button>
                          </div>
                        ))}
                      </div>
                    )}

                    <button
                      type="button"
                      onClick={addSocialLink}
                      className="inline-flex items-center gap-2 text-primary hover:bg-primary/10 px-4 py-2 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary"
                    >
                      <Plus className="w-5 h-5" aria-hidden="true" />
                      Adicionar rede social
                    </button>
                  </div>

                  <div className="mb-0">
                    <label htmlFor="profileImage" className="block mb-2">Foto de perfil</label>

                    {!imagePreview ? (
                      <div>
                        <label
                          htmlFor="profileImage"
                          className="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-border rounded-lg cursor-pointer bg-input-background hover:bg-accent transition-colors focus-within:ring-2 focus-within:ring-primary focus-within:border-transparent"
                        >
                          <div className="flex flex-col items-center justify-center pt-5 pb-6">
                            <Upload className="mb-3 text-muted-foreground" aria-hidden="true" />
                            <p className="mb-2 text-sm text-muted-foreground">
                              <span className="font-semibold">Clique para fazer upload</span> ou arraste e solte
                            </p>
                            <p className="text-xs text-muted-foreground">JPG, PNG ou WebP (máx. 2MB)</p>
                          </div>
                          <input
                            id="profileImage"
                            name="profileImage"
                            type="file"
                            className="sr-only"
                            accept="image/jpeg,image/jpg,image/png,image/webp"
                            onChange={handleImageChange}
                            aria-describedby="image-description"
                          />
                        </label>
                      </div>
                    ) : (
                      <div className="relative">
                        <div className="flex items-center gap-4 p-4 border border-border rounded-lg bg-input-background">
                          <div className="flex-shrink-0">
                            <img
                              src={imagePreview}
                              alt="Preview da foto de perfil"
                              className="w-24 h-24 object-cover rounded-lg"
                            />
                          </div>
                          <div className="flex-1">
                            <p className="font-semibold mb-1">{formData.profileImage?.name}</p>
                            <p className="text-sm text-muted-foreground">
                              {formData.profileImage && (formData.profileImage.size / 1024).toFixed(0)} KB
                            </p>
                          </div>
                          <button
                            type="button"
                            onClick={removeImage}
                            className="flex-shrink-0 text-destructive hover:text-destructive/80 transition-colors p-2 focus:outline-none focus:ring-2 focus:ring-primary rounded-lg"
                            aria-label="Remover imagem"
                          >
                            <X className="w-5 h-5" aria-hidden="true" />
                          </button>
                        </div>
                      </div>
                    )}

                    {imageError && (
                      <p className="text-sm text-destructive mt-2" role="alert" aria-live="polite">
                        {imageError}
                      </p>
                    )}

                    <p id="image-description" className="text-sm text-muted-foreground mt-2">
                      Adicione uma foto ou logotipo que represente seu perfil. Formatos aceitos: JPG, PNG, WebP.
                      Tamanho máximo: 2MB.
                    </p>
                  </div>
                </div>

                <div className="border border-border rounded-lg p-6 bg-[#ffffff]">
                  <h2 className="text-2xl mb-6">Email de contato</h2>
                  <label htmlFor="email" className="block mb-2">
                    Email <span className="text-destructive" aria-label="obrigatório">*</span>
                  </label>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                    required
                    className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                    placeholder="seu@email.com"
                    aria-required="true"
                    aria-describedby="email-description"
                  />
                  <p id="email-description" className="text-sm text-muted-foreground mt-2">
                    O email será privado e utilizado apenas para que a organização do a11yBR possa entrar em
                    contato com a pessoa que realizou a submissão.
                  </p>
                </div>

                <div className="pt-4">
                  <button
                    type="submit"
                    className="bg-primary text-primary-foreground px-8 py-4 rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
                  >
                    Criar perfil
                  </button>
                </div>
              </form>
            </div>

            <div className="lg:col-span-1 space-y-6">
              <div className="bg-primary border text-primary-foreground border-border rounded-lg p-6">
                <h2 className="text-xl mb-4">Conteúdos aceitos</h2>
                <ol className="space-y-2">
                  <li className="flex items-start gap-2">1. Atuação real em acessibilidade</li>
                  <li className="flex items-start gap-2">2. Informações verificáveis</li>
                  <li className="flex items-start gap-2">3. Links válidos</li>
                  <li className="flex items-start gap-2">4. Alinhamento com a comunidade</li>
                </ol>
              </div>

              <div className="border border-border rounded-lg p-6">
                <h2 className="text-xl mb-4">Processo de validação</h2>
                <ol className="space-y-3">
                  <li className="flex gap-3">1. Envio do perfil</li>
                  <li className="flex gap-3">2. Verificação das informações</li>
                  <li className="flex gap-3">3. Contato se necessário</li>
                  <li className="flex gap-3">4. Publicação no diretório</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
