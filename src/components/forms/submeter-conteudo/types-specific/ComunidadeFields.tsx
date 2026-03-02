import { FormState } from "../types";

type Props = {
  form: FormState;
  onChange: (field: keyof FormState, value: any) => void;
};

export function ComunidadeFields({ form, onChange }: Props) {
  return (
    <fieldset className="space-y-4">
      <legend className="block text-lg font-medium">Detalhes da Comunidade</legend>
      <div className="space-y-2">
        <label className="block text-sm font-medium">
          Qual plataforma a comunidade utiliza para interação?
          <span className="text-destructive">*</span>

        </label>
        <select
          className="input-base"
          value={form.plataforma || ""}
          onChange={(e) => onChange("plataforma", e.target.value)}
          required
        >
          <option value="">Plataforma</option>
          <option value="discord">Discord</option>
          <option value="slack">Slack</option>
          <option value="whatsapp">WhatsApp</option>
          <option value="telegram">Telegram</option>
          <option value="linkedin">LinkedIn</option>
        </select>
      </div>
      <div className="space-y-2">
        <label className="block text-sm font-medium">
          Tipo de acesso à comunidade
          <span className="text-destructive">*</span>

        </label>
        <select
          className="input-base"
          value={form.acesso || ""}
          onChange={(e) => onChange("acesso", e.target.value)}
          required
        >
          <option value="">Tipo de acesso</option>
          <option value="aberto">Aberto</option>
          <option value="convite">Mediante convite</option>
        </select>
      </div>
    </fieldset>
  );
}