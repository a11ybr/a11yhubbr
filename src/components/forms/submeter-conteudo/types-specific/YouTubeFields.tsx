import { FormState } from "../types";

type Props = {
  form: FormState;
  onChange: (field: keyof FormState, value: any) => void;
};

export function YouTubeFields({ form, onChange }: Props) {
  return (
    <fieldset className="space-y-4">
      <legend className="block text-lg font-medium">Canal do Canal YouTube</legend>

      <select
        className="input-base"
        value={form.focoCanal || ""}
        onChange={(e) => onChange("focoCanal", e.target.value)}
        required
      >
        <option value="">Foco do canal</option>
        <option value="tecnico">Técnico</option>
        <option value="educacional">Educacional</option>
        <option value="ativismo">Ativismo</option>
        <option value="ux">UX</option>
      </select>

      <select
        className="input-base"
        value={form.tipoConteudoYT || ""}
        onChange={(e) => onChange("tipoConteudoYT", e.target.value)}
        required
      >
        <option value="">Tipo de conteúdo</option>
        <option value="tutorial">Tutoriais</option>
        <option value="entrevista">Entrevistas</option>
        <option value="analise">Análises</option>
        <option value="vlog">Vlogs</option>
      </select>
    </fieldset>
  );
}