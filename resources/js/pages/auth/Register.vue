<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
</script>

<template>
    <AuthBase
        title="Registro de Cliente"
        description="Ingresa tus datos personales y de acceso para crear tu cuenta"
    >
        <Head title="Registro de Cliente" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label for="first_name">Nombres</Label>
                        <Input
                            id="first_name"
                            type="text"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="given-name"
                            name="first_name"
                            placeholder="Tus nombres"
                        />
                        <InputError :message="errors.first_name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="last_name">Apellidos</Label>
                        <Input
                            id="last_name"
                            type="text"
                            required
                            :tabindex="2"
                            autocomplete="family-name"
                            name="last_name"
                            placeholder="Tus apellidos"
                        />
                        <InputError :message="errors.last_name" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label for="document_type">Tipo de Documento</Label>
                        <select 
                            id="document_type" 
                            name="document_type" 
                            required 
                            :tabindex="3"
                            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="CC">Cédula de Ciudadanía</option>
                            <option value="CE">Cédula de Extranjería</option>
                            <option value="NIT">NIT</option>
                            <option value="PAS">Pasaporte</option>
                        </select>
                        <InputError :message="errors.document_type" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="document_number">Número de Documento</Label>
                        <Input
                            id="document_number"
                            type="text"
                            required
                            :tabindex="4"
                            name="document_number"
                            placeholder="Ej: 1020304050"
                        />
                        <InputError :message="errors.document_number" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label for="phone">Teléfono / Celular</Label>
                        <Input
                            id="phone"
                            type="tel"
                            required
                            :tabindex="5"
                            name="phone"
                            placeholder="Ej: 3001234567"
                        />
                        <InputError :message="errors.phone" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="address">Dirección Completa</Label>
                        <Input
                            id="address"
                            type="text"
                            required
                            :tabindex="6"
                            name="address"
                            placeholder="Ej: Calle Falsa 123"
                        />
                        <InputError :message="errors.address" />
                    </div>
                </div>

                <div class="border-t pt-4 mt-2">
                    <h3 class="text-sm font-medium text-muted-foreground mb-4">Credenciales de acceso</h3>
                    
                    <div class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="email">Correo electrónico</Label>
                            <Input
                                id="email"
                                type="email"
                                required
                                :tabindex="7"
                                autocomplete="email"
                                name="email"
                                placeholder="correo@ejemplo.com"
                            />
                            <InputError :message="errors.email" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="grid gap-2">
                                <Label for="password">Contraseña</Label>
                                <Input
                                    id="password"
                                    type="password"
                                    required
                                    :tabindex="8"
                                    autocomplete="new-password"
                                    name="password"
                                    placeholder="Contraseña"
                                />
                                <InputError :message="errors.password" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="password_confirmation">Confirmar contraseña</Label>
                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    required
                                    :tabindex="9"
                                    autocomplete="new-password"
                                    name="password_confirmation"
                                    placeholder="Confirmar contraseña"
                                />
                                <InputError :message="errors.password_confirmation" />
                            </div>
                        </div>
                    </div>
                </div>

                <Button
                    type="submit"
                    class="mt-4 w-full"
                    :tabindex="10"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <Spinner v-if="processing" />
                    Crear cuenta
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                ¿Ya tienes una cuenta?
                <TextLink
                    :href="login()"
                    class="underline underline-offset-4"
                    :tabindex="11"
                    >Inicia sesión</TextLink
                >
            </div>
        </Form>
    </AuthBase>
</template>